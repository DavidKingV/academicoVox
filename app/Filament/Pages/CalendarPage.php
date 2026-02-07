<?php

namespace App\Filament\Pages;

use App\Models\Event;
use App\Models\Period;
use App\Models\Room;
use App\Models\Teacher;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class CalendarPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|UnitEnum|null $navigationGroup = 'Calendar';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.calendar';

    public ?int $selectedPeriodId = null;

    public string $mode = 'teacher';

    public ?int $selectedTeacherId = null;

    public ?int $selectedRoomId = null;

    /** @var array<int, array<string, mixed>> */
    public array $teachers = [];

    /** @var array<int, array<string, mixed>> */
    public array $rooms = [];

    /** @var array<int, array<string, mixed>> */
    public array $events = [];

    public function mount(): void
    {
        $period = Period::get_default_period();
        $this->selectedPeriodId = $period?->id;
        $this->teachers = Teacher::with('user')
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->user?->name ?? 'Teacher #'.$t->id,
            ])
            ->sortBy('name')
            ->values()
            ->toArray();

        $this->rooms = Room::orderBy('name')
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'name' => $r->name,
            ])
            ->toArray();

        if (count($this->teachers) > 0) {
            $this->selectedTeacherId = $this->teachers[0]['id'];
        }

        $this->loadEvents();
    }

    public function updatedSelectedPeriodId(): void
    {
        $this->loadEvents();
        $this->dispatch('eventsUpdated', events: $this->events);
    }

    public function updatedSelectedTeacherId(): void
    {
        $this->mode = 'teacher';
        $this->loadEvents();
        $this->dispatch('eventsUpdated', events: $this->events);
    }

    public function updatedSelectedRoomId(): void
    {
        $this->mode = 'room';
        $this->loadEvents();
        $this->dispatch('eventsUpdated', events: $this->events);
    }

    protected function loadEvents(): void
    {
        $query = Event::with(['course', 'teacher.user', 'room'])
            ->whereHas('course', fn ($q) => $q->where('period_id', $this->selectedPeriodId));

        if ($this->mode === 'teacher' && $this->selectedTeacherId) {
            $query->where('teacher_id', $this->selectedTeacherId);
        } elseif ($this->mode === 'room' && $this->selectedRoomId) {
            $query->where('room_id', $this->selectedRoomId);
        }

        $this->events = $query->get()
            ->map(fn ($event) => [
                'id' => $event->id,
                'title' => $event->course?->name ?? $event->name ?? 'Event',
                'start' => $event->start?->toIso8601String(),
                'end' => $event->end?->toIso8601String(),
                'color' => $event->course?->color ?? '#3b82f6',
                'teacher' => $event->teacher?->user?->name ?? '',
                'room' => $event->room?->name ?? '',
            ])
            ->toArray();
    }

    public static function getNavigationLabel(): string
    {
        return __('Calendar');
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return __('Calendar');
    }
}
