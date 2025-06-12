<?php

namespace App\Services\GeneticAlgorithm;

class Population
{
    protected array $schedules;

    public function __construct(array $schedules)
    {
        $this->schedules = $schedules;
    }

    public function getSchedules(): array
    {
        return $this->schedules;
    }

    public function setSchedules(array $schedules): void
    {
        $this->schedules = $schedules;
    }

    public function getFittest(): Schedule
    {
        usort($this->schedules, fn($a, $b) => $b->fitness <=> $a->fitness);
        return $this->schedules[0];
    }
}
