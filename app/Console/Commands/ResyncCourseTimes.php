<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResyncCourseTimes extends Command
{
    protected $signature = 'academico:resync-coursetimes {course_ids*}';

    protected $description = 'In case events are not in sync with course times, re-create events for specified courses.';

    public function handle(): int
    {
        foreach ($this->argument('course_ids') as $courseId) {
            $course = Course::find($courseId);

            if (! $course) {
                $this->error("Course {$courseId} not found.");

                continue;
            }

            DB::table('events')->where('course_id', $courseId)->delete();

            $courseStartDate = Carbon::parse($course->start_date)->startOfDay();
            $courseEndDate = Carbon::parse($course->end_date)->startOfDay();

            while ($courseStartDate < $courseEndDate) {
                $courseTime = $course->times->where('day', $courseStartDate->format('w'))->first();

                if ($courseTime) {
                    Event::create([
                        'course_id' => $course->id,
                        'teacher_id' => $course->teacher_id,
                        'room_id' => $course->room_id,
                        'start' => $courseStartDate->copy()->setTimeFromTimeString($courseTime->start)->toDateTimeString(),
                        'end' => $courseStartDate->copy()->setTimeFromTimeString($courseTime->end)->toDateTimeString(),
                        'name' => $course->name,
                        'course_time_id' => $courseTime->id,
                        'exempt_attendance' => $course->exempt_attendance,
                    ]);
                }

                $courseStartDate->addDay();
            }

            $this->info("Course {$courseId} events re-synced.");
        }

        return Command::SUCCESS;
    }
}
