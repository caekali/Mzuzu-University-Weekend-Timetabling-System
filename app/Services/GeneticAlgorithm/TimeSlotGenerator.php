<?php

namespace App\Services\GeneticAlgorithm;


class TimeSlotGenerator
{
    public static function generate(array $days = ['Friday', 'Saturday', 'Sunday'], string $start = '07:45', string $end = '18:45', int $slotMinutes = 60): array
    {
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

                $current = $next;
            }
        }
        return $slots;
    }
}
