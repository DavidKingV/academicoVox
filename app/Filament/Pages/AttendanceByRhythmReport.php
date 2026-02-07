<?php

namespace App\Filament\Pages;

use App\Models\AttendanceType;
use App\Models\Course;
use App\Models\Period;
use App\Models\Rhythm;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class AttendanceByRhythmReport extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bars-3-bottom-right';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 12;

    protected string $view = 'filament.pages.attendance-by-rhythm-report';

    public ?int $selectedPeriodId = null;

    /** @var array<string, mixed> */
    public array $chartData = [];

    /** @var array<int, array<string, mixed>> */
    public array $tableData = [];

    /** @var array<int, array<string, mixed>> */
    public array $attendanceTypes = [];

    public function mount(): void
    {
        $period = Period::get_default_period();
        $this->selectedPeriodId = $period?->id;

        $this->attendanceTypes = AttendanceType::all()
            ->map(fn ($t) => ['id' => $t->id, 'name' => $t->name, 'color' => $t->color ?? '#6b7280'])
            ->toArray();

        $this->loadData();
    }

    public function updatedSelectedPeriodId(): void
    {
        $this->loadData();
    }

    protected function loadData(): void
    {
        if (! $this->selectedPeriodId) {
            return;
        }

        $courses = Course::where('period_id', $this->selectedPeriodId)
            ->has('attendance')
            ->has('events')
            ->with(['attendance', 'rhythm'])
            ->get();

        $groups = $courses->groupBy('rhythm_id')
            ->filter(fn ($g, $k) => Rhythm::find($k) !== null);

        $labels = [];
        $datasets = [];
        $tableRows = [];

        foreach ($this->attendanceTypes as $type) {
            $datasets[$type['id']] = [
                'label' => $type['name'],
                'data' => [],
                'backgroundColor' => $type['color'],
            ];
        }

        foreach ($groups as $rhythmId => $groupCourses) {
            $rhythm = Rhythm::find($rhythmId);
            $labels[] = $rhythm->name;

            $totalAttendance = 0;
            $countsByType = [];

            foreach ($this->attendanceTypes as $type) {
                $countsByType[$type['id']] = 0;
            }

            foreach ($groupCourses as $course) {
                $totalAttendance += $course->attendance->count();
                foreach ($this->attendanceTypes as $type) {
                    $countsByType[$type['id']] += $course->attendance->where('attendance_type_id', $type['id'])->count();
                }
            }

            $row = ['rhythm' => $rhythm->name, 'total' => $totalAttendance];

            foreach ($this->attendanceTypes as $type) {
                $pct = $totalAttendance > 0 ? round(100 * $countsByType[$type['id']] / $totalAttendance) : 0;
                $datasets[$type['id']]['data'][] = $pct;
                $row['type_'.$type['id']] = $pct.'%';
            }

            $tableRows[] = $row;
        }

        $this->tableData = $tableRows;
        $this->chartData = [
            'labels' => $labels,
            'datasets' => array_values($datasets),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('Attendance by Rhythm');
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return __('Attendance by Rhythm');
    }
}
