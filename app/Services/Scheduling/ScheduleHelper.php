<?php

namespace App\Services\Scheduling;

class ScheduleHelper
{
     public static function generateTimeSlots(string $start, string $end, int $slotMinutes = 30): array
    {
        $startTime = strtotime($start);
        $endTime = strtotime($end);
        $slots = [];

        for ($time = $startTime; $time < $endTime; $time += $slotMinutes * 60) {
            $slots[] = date('H:i', $time);
        }

        return $slots;
    }
}
