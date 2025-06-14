<?php

namespace App\Services\GeneticAlgorithm;

use App\Models\Course;
use App\Models\Venue;

class GADataLoaderService
{
    public function loadGAData(): array
    {
        return [
            'venues'    => $this->getVenues(),
            'courses'   => $this->getCourses(),
            'timeslots' => $this->generateTimeslots(), // or fetch
        ];
    }

    protected function getVenues(): array
    {
        return Venue::select('id', 'name', 'capacity')
            ->get()
            ->map(fn($v) => (object)[
                'id'       => $v->id,
                'name'     => $v->name,
                'capacity' => $v->capacity,
            ])
            ->toArray();
    }

    protected function getCourses(): array
    {
        return Course::select('id', 'code', 'name', 'weekly_hours', 'num_of_students')
            ->get()
            ->map(fn($c) => (object)[
                'id'             => $c->id,
                'code'           => $c->code,
                'name'           => $c->name,
                'weekly_hours'   => $c->weekly_hours,
                'num_of_students' => $c->num_of_students,
                'lecturer'    => rand(1, 4),
            ])
            ->toArray();
    }

    protected function generateTimeslots(): array
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $slotsPerDay = 5;

        $slots = [];
        foreach ($days as $dayIndex => $day) {
            for ($i = 0; $i < $slotsPerDay; $i++) {
                $slots[] = (object)[
                    'id'   => ($dayIndex * $slotsPerDay) + $i + 1,
                    'day'  => $day,
                    'slot' => $i + 1,
                ];
            }
        }

        return $slots;
    }
}
