<?php

namespace App\Services\GA;

class Matator
{
    class Mutator
{
    public function mutate(Schedule $schedule, float $mutationRate): Schedule
    {
        $entries = $schedule->getEntries();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $times = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'];

        foreach ($entries as &$entry) {
            if (mt_rand() / mt_getrandmax() < $mutationRate) {
                $entry['day'] = $days[array_rand($days)];
                $start = $times[array_rand($times)];
                $entry['start_time'] = $start;
                $entry['end_time'] = date("H:i", strtotime("+1 hour", strtotime($start)));
            }
        }

        return new Schedule($entries);
    }
}
}
