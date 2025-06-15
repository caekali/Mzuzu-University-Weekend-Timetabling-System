<?php

namespace App\Services\GeneticAlgorithm;

use App\Models\Course;
use App\Models\Venue;
use App\DTO\VenueDTO;
use App\DTO\CourseDTO;
use App\DTO\TimeSlotDTO;

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
        return Course::select('id', 'code', 'name', 'weekly_hours', 'num_of_students')
            ->get()
            ->map(fn($c) => new CourseDTO(
                $c->id,
                $c->code,
                $c->name,
                $c->weekly_hours,
                $c->num_of_students,
                rand(1, 5), // Simulated lecturer_id
            ))
            ->toArray();
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
