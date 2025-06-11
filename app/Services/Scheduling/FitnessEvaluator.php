<?php

namespace App\Services\Scheduling;

class FitnessEvaluator
{
    public function evaluate(Schedule $schedule): int
    {
        $score = 1000; // Start with perfect score

        $lecturerSessions = [];
        $venueSessions = [];
        $programmeSessions = [];

        foreach ($schedule->entries as $entry) {
            $lecturerId = $entry['lecturer_id'];
            $venueId = $entry['venue_id'];
            $programmeId = $entry['programme_id'] ?? null; // Include if you're storing this
            $timeSlots = $entry['time_slots'];

            foreach ($timeSlots as $slot) {
                $day = $slot['day'];
                $start = $slot['start_time'];

                // Create a unique key
                $key = "{$day}-{$start}";

                // Check lecturer conflict
                if (isset($lecturerSessions[$lecturerId][$key])) {
                    $score -= 20;
                } else {
                    $lecturerSessions[$lecturerId][$key] = true;
                }

                // Check venue conflict
                if (isset($venueSessions[$venueId][$key])) {
                    $score -= 20;
                } else {
                    $venueSessions[$venueId][$key] = true;
                }

                // Check programme conflict
                if ($programmeId && isset($programmeSessions[$programmeId][$key])) {
                    $score -= 20;
                } else {
                    $programmeSessions[$programmeId][$key] = true;
                }

                // Count daily sessions per lecturer
                $dailyKey = "{$lecturerId}-{$day}";
                $lecturerDailyCount[$dailyKey] = ($lecturerDailyCount[$dailyKey] ?? 0) + 1;

                if ($lecturerDailyCount[$dailyKey] > 3) {
                    $score -= 10;
                }
            }
        }

        // Optional: If total score drops below 0, set to 0
        return max($score, 0);
    }
}

