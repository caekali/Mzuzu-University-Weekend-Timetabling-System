<?php

namespace App\Services\GA;

class Schedule
{
    public array $entries = [];
    

    public static function generateRandom(array $courses, array $venues): self
    {
        $schedule = new self();

        foreach ($courses as $course) {
            $venue = $venues[array_rand($venues)];
            $lecturerId = $course['allocations'][0]['lecturer_id'] ?? null;
            $programmeId = $course['programmes'][0]['id'] ?? null;

            $schedule->entries[] = [
                'course_id' => $course['id'],
                'lecturer_id' => $lecturerId,
                'venue_id' => $venue['id'],
                'programme_id' => $programmeId,
                'time_slots' => [
                    [
                        'day' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'][array_rand([0, 1, 2, 3, 4])],
                        'start_time' => sprintf('%02d:00:00', rand(8, 16))
                    ]
                ]
            ];
        }

        return $schedule;
    }


    public function mutate(float $mutationRate, array $venues): void
    {
        foreach ($this->entries as &$entry) {
            if (mt_rand() / mt_getrandmax() < $mutationRate) {
                $entry['venue_id'] = $venues[array_rand($venues)]['id'];
                $entry['time_slots'][0]['day'] = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'][array_rand([0, 1, 2, 3, 4])];
                $entry['time_slots'][0]['start_time'] = sprintf('%02d:00:00', rand(8, 16));
            }
        }
    }


    public function crossover(Schedule $partner): Schedule
    {
        $child = new self();
        $midpoint = floor(count($this->entries) / 2);

        $child->entries = array_merge(
            array_slice($this->entries, 0, $midpoint),
            array_slice($partner->entries, $midpoint)
        );

        return $child;
    }

    public function fitness(): int
    {
        return (new FitnessEvaluator())->evaluate($this);
    }

    public function toArray(): array
    {
        return $this->entries;
    }




}
