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
                rand(1, 4), // Simulated lecturer_id
            ))
            ->toArray();
    }

    protected function generateTimeslots(): array
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $slotsPerDay = 5;

        $slots = [];
        foreach ($days as $dayIndex => $day) {
            for ($i = 0; $i < $slotsPerDay; $i++) {
                $slots[] = new TimeSlotDTO(
                    ($dayIndex * $slotsPerDay) + $i + 1,
                    $day,
                    $i + 1
                );
            }
        }
        return $slots;
    }
}
