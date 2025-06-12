<?php

namespace App\Services\GA;

class FitnessEvaluator
{
      public function evaluate(Schedule $schedule): int
    {
        $score = 1000;

        $lecturerSessions = [];
        $venueSessions = [];
        $programmeSessions = [];
        $lecturerDailyCount = [];

        foreach ($schedule->getEntries() as $entry) {
            $lecturerId = $entry['lecturer_id'];
            $venueId = $entry['venue_id'];
            $programmeId = $entry['programme_id'];
            $day = $entry['day'];
            $start = $entry['start_time'];

            $key = "$day-$start";

            if (isset($lecturerSessions[$lecturerId][$key])) {
                $score -= 20;
            } else {
                $lecturerSessions[$lecturerId][$key] = true;
            }

            if (isset($venueSessions[$venueId][$key])) {
                $score -= 20;
            } else {
                $venueSessions[$venueId][$key] = true;
            }

            if (isset($programmeSessions[$programmeId][$key])) {
                $score -= 20;
            } else {
                $programmeSessions[$programmeId][$key] = true;
            }

            $dailyKey = "$lecturerId-$day";
            $lecturerDailyCount[$dailyKey] = ($lecturerDailyCount[$dailyKey] ?? 0) + 1;

            if ($lecturerDailyCount[$dailyKey] > 3) {
                $score -= 10;
            }
        }

        return max($score, 0);
    }
}
