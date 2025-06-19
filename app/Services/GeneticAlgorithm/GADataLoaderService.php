<?php

namespace App\Services\GeneticAlgorithm;

use App\Models\Venue;
use App\DTO\VenueDTO;
use App\DTO\CourseDTO;
use App\DTO\TimeSlotDTO;
use App\Models\ScheduleDay;
use App\Models\LecturerCourseAllocation;
use Carbon\Carbon;

use function App\Helpers\getSetting;

class GADataLoaderService
{

    public function loadGAData(): array
    {
        return [
            'venues'    => $this->getVenues(),
            'courses'   => $this->getCourses(),
            'timeslots' => $this->generateTimeslots(),
        ];
    }

    protected function getVenues(): array
    {
        return Venue::select('id', 'name', 'capacity')
            ->get()
            ->map(fn($v) => new VenueDTO($v->id, $v->name, $v->capacity))
            ->toArray();
    }

    protected function getCourses(): array
    {
        $allocations = LecturerCourseAllocation::with([
            'lecturer',
            'course',
            'programmes'
        ])->get();
        $courseData = $allocations->map(function ($allocation) {
            $programmes = $allocation->programmes->map(function ($programme) use ($allocation) {
                return $programme->code . $allocation->course->level . $allocation->course->semester;
            })->toArray();

            return new CourseDTO(
                $allocation->course->id,
                $allocation->course->code,
                $allocation->course->weekly_hours,
                $allocation->course->num_of_students,
                $allocation->lecturer->id,
                $programmes
            );
        })->toArray();
        return $courseData;
    }


    public static function generateTimeslots(): array
    {
        $slots = [];
        $days = ScheduleDay::where('enabled', true)->get();
        $slotMinutes = getSetting('slot_duration');
        foreach ($days as $day) {
            $current = strtotime($day->start_time);
            $endTime = strtotime($day->end_time);
            while ($current < $endTime) {
                $next = strtotime("+{$slotMinutes} minutes", $current);
                if ($next > $endTime) {
                    break;
                }
                $slots[] = [
                    'day' => $day->name,
                    'start' => date('H:i', $current),
                    'end' => date('H:i', $next),
                ];

                $current = $next;
            }
        }
        return $slots;
    }
}
