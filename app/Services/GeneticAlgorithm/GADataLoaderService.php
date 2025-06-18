<?php

namespace App\Services\GeneticAlgorithm;

use App\Models\Venue;
use App\DTO\VenueDTO;
use App\DTO\CourseDTO;
use App\DTO\TimeSlotDTO;
use App\Models\LecturerCourseAllocation;

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
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $start = '07:45';
        $end = '18:45';
        $slotMinutes = 60;
        $slots = [];

        foreach ($days as $day) {
            $current = strtotime($start);
            $endTime = strtotime($end);

            while ($current < $endTime) {
                $next = strtotime("+$slotMinutes minutes", $current);

                $slots[] = [
                    'day' => $day,
                    'start' => date('H:i', $current),
                    'end' => date('H:i', $next),
                ];

                //  $slots[] = new TimeSlotDTO(
                //     ($dayIndex * $slotsPerDay) + $i + 1,
                //     $day,
                //     $i + 1
                // );

                $current = $next;
            }
        }
        return $slots;
    }
}
