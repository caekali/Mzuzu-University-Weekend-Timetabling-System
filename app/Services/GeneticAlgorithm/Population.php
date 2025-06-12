<?php

namespace App\Services\GeneticAlgorithm;

class Population
{
    public array $schedules;

    public function __construct(array $schedules = [])
    {
        $this->schedules = $schedules;
    }

    public static function initialize(int $size, array $courses, array $venues, array $timeSlots): self
    {
        $schedules = [];

        for ($i = 0; $i < $size; $i++) {
            $schedule = Schedule::random($courses, $venues, $timeSlots);
            $schedule->calculateFitness();
            $schedules[] = $schedule;
        }

        return new self($schedules);
    }

    public function getFittest(): Schedule
    {
        usort($this->schedules, fn($a, $b) => $b->fitness <=> $a->fitness);
        return $this->schedules[0];
    }

    public function selectParent(): Schedule
    {
        $a = $this->schedules[array_rand($this->schedules)];
        $b = $this->schedules[array_rand($this->schedules)];

        return $a->fitness > $b->fitness ? $a : $b;
    }
}
