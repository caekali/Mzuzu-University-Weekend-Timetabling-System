<?php

namespace App\Services\GeneticAlgorithm;



class Population
{
    private array $schedules = [];

    public function __construct(array $schedules = [])
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

    public function addSchedule(Schedule $schedule): void
    {
        $this->schedules[] = $schedule;
    }

    public function getSchedule(int $index): Schedule
    {
        return $this->schedules[$index];
    }

    public function getFittest(): Schedule
    {
        usort($this->schedules, fn($a, $b) => $b->getFitness() <=> $a->getFitness());
        return $this->schedules[0];
    }

    public function count(): int
    {
        return count($this->schedules);
    }
}
