<?php

namespace App\Filament\Pages;

use App\Models\Course;
use App\Models\Enrollment;
use BackedEnum;
use Filament\Pages\Page;

class CourseAttendance extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-table-cells';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.course-attendance';

    protected static bool $shouldRegisterNavigation = false;

    public ?int $courseId = null;

    public ?Course $course = null;

    /** @var array<int, array<string, mixed>> */
    public array $events = [];

    /** @var array<int, array<string, mixed>> */
    public array $students = [];

    public function mount(?int $courseId = null): void
    {
        $this->courseId = $courseId;

        if ($courseId) {
            $this->loadData();
        }
    }

    protected function loadData(): void
    {
        $this->course = Course::with(['events' => fn ($q) => $q->orderBy('start'), 'enrollments.student'])
            ->find($this->courseId);

        if (! $this->course) {
            return;
        }

        $this->events = $this->course->events
            ->map(fn ($event) => [
                'id' => $event->id,
                'name' => $event->name ?? $event->start?->format('d/m'),
                'date' => $event->start?->format('Y-m-d'),
            ])
            ->toArray();

        $eventIds = collect($this->events)->pluck('id')->toArray();

        $enrollments = Enrollment::with(['student', 'course'])
            ->where('course_id', $this->courseId)
            ->get();

        $allAttendance = \App\Models\Attendance::whereIn('event_id', $eventIds)
            ->whereIn('student_id', $enrollments->pluck('student_id'))
            ->with('attendanceType')
            ->get()
            ->groupBy(fn ($a) => $a->student_id.'-'.$a->event_id);

        $studentsData = [];
        foreach ($enrollments as $enrollment) {
            $eventAttendances = [];
            foreach ($eventIds as $eventId) {
                $key = $enrollment->student_id.'-'.$eventId;
                $att = $allAttendance->get($key)?->first();
                $eventAttendances[$eventId] = $att ? [
                    'type' => $att->attendanceType?->name ?? '?',
                    'typeId' => $att->attendance_type_id,
                ] : null;
            }

            $studentsData[] = [
                'studentId' => $enrollment->student_id,
                'studentName' => $enrollment->student?->name ?? '',
                'enrollmentId' => $enrollment->id,
                'attendances' => $eventAttendances,
            ];
        }

        $this->students = $studentsData;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Attendance');
    }

    public static function getNavigationLabel(): string
    {
        return __('Course Attendance');
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return $this->course
            ? __('Attendance').': '.$this->course->name
            : __('Course Attendance');
    }
}
