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



    //   public function evaluate(Schedule $schedule): int
    // {
    //     $score = 1000; // Start with perfect score

    //     $lecturerSessions = [];
    //     $venueSessions = [];
    //     $programmeSessions = [];

    //     foreach ($schedule->entries as $entry) {
    //         $lecturerId = $entry['lecturer_id'];
    //         $venueId = $entry['venue_id'];
    //         $programmeId = $entry['programme_id'] ?? null; // Include if you're storing this
    //         $timeSlots = $entry['time_slots'];

    //         foreach ($timeSlots as $slot) {
    //             $day = $slot['day'];
    //             $start = $slot['start_time'];

    //             // Create a unique key
    //             $key = "{$day}-{$start}";

    //             // Check lecturer conflict
    //             if (isset($lecturerSessions[$lecturerId][$key])) {
    //                 $score -= 20;
    //             } else {
    //                 $lecturerSessions[$lecturerId][$key] = true;
    //             }

    //             // Check venue conflict
    //             if (isset($venueSessions[$venueId][$key])) {
    //                 $score -= 20;
    //             } else {
    //                 $venueSessions[$venueId][$key] = true;
    //             }

    //             // Check programme conflict
    //             if ($programmeId && isset($programmeSessions[$programmeId][$key])) {
    //                 $score -= 20;
    //             } else {
    //                 $programmeSessions[$programmeId][$key] = true;
    //             }

    //             // Count daily sessions per lecturer
    //             $dailyKey = "{$lecturerId}-{$day}";
    //             $lecturerDailyCount[$dailyKey] = ($lecturerDailyCount[$dailyKey] ?? 0) + 1;

    //             if ($lecturerDailyCount[$dailyKey] > 3) {
    //                 $score -= 10;
    //             }
    //         }
    //     }

    //     // Optional: If total score drops below 0, set to 0
    //     return max($score, 0);
    // }



    //  public function evaluate(Schedule $schedule): int
    // {
    //     $score = 1000;

    //     $lecturerSessions = [];
    //     $venueSessions = [];
    //     $programmeSessions = [];
    //     $lecturerDailyCount = [];

    //     foreach ($schedule->getEntries() as $entry) {
    //         $lecturerId = $entry['lecturer_id'];
    //         $venueId = $entry['venue_id'];
    //         $programmeId = $entry['programme_id'];
    //         $day = $entry['day'];
    //         $start = $entry['start_time'];

    //         $key = "$day-$start";

    //         if (isset($lecturerSessions[$lecturerId][$key])) {
    //             $score -= 20;
    //         } else {
    //             $lecturerSessions[$lecturerId][$key] = true;
    //         }

    //         if (isset($venueSessions[$venueId][$key])) {
    //             $score -= 20;
    //         } else {
    //             $venueSessions[$venueId][$key] = true;
    //         }

    //         if (isset($programmeSessions[$programmeId][$key])) {
    //             $score -= 20;
    //         } else {
    //             $programmeSessions[$programmeId][$key] = true;
    //         }

    //         $dailyKey = "$lecturerId-$day";
    //         $lecturerDailyCount[$dailyKey] = ($lecturerDailyCount[$dailyKey] ?? 0) + 1;

    //         if ($lecturerDailyCount[$dailyKey] > 3) {
    //             $score -= 10;
    //         }
    //     }

    //     return max($score, 0);
    // }
}
