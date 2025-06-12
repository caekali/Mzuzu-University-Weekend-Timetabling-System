<?php

namespace App\Services\GeneticAlgorithm;

class FitnessEvaluator
{
    public function evaluate(Schedule $schedule): float
    {
        $penalty = 0;
        $entries = $schedule->scheduleEntries;

        $timeslotLecturerMap = [];
        $timeslotVenueMap = [];
        $timeslotProgrammeMap = [];

        foreach ($entries as $entry) {
            $key = $entry->timeSlot->id;

            // Lecturer conflict
            $lecturerId = $entry->lecturer->id;
            if (isset($timeslotLecturerMap[$key][$lecturerId])) {
                $penalty += 10;
            }
            $timeslotLecturerMap[$key][$lecturerId] = true;

            // Venue conflict
            $venueId = $entry->venue->id;
            if (isset($timeslotVenueMap[$key][$venueId])) {
                $penalty += 10;
            }
            $timeslotVenueMap[$key][$venueId] = true;

            // Student group conflict (based on course.programmes)
            foreach ($entry->course->programmes as $programme) {
                $progId = $programme->id;
                if (isset($timeslotProgrammeMap[$key][$progId])) {
                    $penalty += 10;
                }
                $timeslotProgrammeMap[$key][$progId] = true;
            }

            // Room capacity
            if ($entry->venue->capacity < $entry->course->expected_students) {
                $penalty += 5;
            }
        }

        return 1 / (1 + $penalty); // Fitness: higher is better
    }
}
