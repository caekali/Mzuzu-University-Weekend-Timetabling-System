<?php

namespace App\Services\Scheduling;

class TimeSlotGenerator
{
    public static function generate(array $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'], string $start = '09:00', string $end = '17:00'): array
    {
        $slots = [];

        foreach ($days as $day) {
            $current = strtotime($start);
            $endTime = strtotime($end);

            while ($current < $endTime) {
                $next = strtotime('+1 hour', $current);

                $slots[] = [
                    'day' => $day,
                    'start_time' => date('H:i', $current),
                    'end_time' => date('H:i', $next),
                ];

                $current = $next;
            }
        }

        return $slots;
    }
}
