<?php

namespace App\Services\GeneticAlgorithm;

class GeneticAlgorithm
{
    public function __construct(
        protected int $populationSize,
        protected int $eliteSchedules,
        protected float $crossoverRate,
        protected float $mutationRate,
        protected int $tournamentSize,
        protected $data
    ) {}

    public function initializePopulation(): Population
    {
        $schedules = [];
        for ($i = 0; $i < $this->populationSize; $i++) {
            $schedules[] = Schedule::generateRandomSchedule($this->data);
        }
        return new Population($schedules);
    }

    public function evolve(Population $population): Population
    {
        $crossover = $this->generateCrossoverPopulation($population);
        return $this->applyMutation($crossover);
    }

    private function generateCrossoverPopulation(Population $pop): Population
    {
        $newPop = new Population();

        for ($i = 0; $i < $this->eliteSchedules; $i++) {
            $newPop->addSchedule(clone $pop->getSchedule($i));
        }

        // Fill remaining schedules using crossover
        while ($newPop->count() < $this->populationSize) {
            $parent1 = $this->tournamentSelection($pop);
            $parent2 = $this->tournamentSelection($pop);
            $child = $this->crossoverSchedules($parent1, $parent2);
            $newPop->addSchedule($child);
        }

        return $newPop;
    }

    private function applyMutation(Population $population): Population
    {
        $schedules = $population->getSchedules();

        for ($i = $this->eliteSchedules; $i < $this->populationSize; $i++) {
            $this->mutateSchedule($schedules[$i]);
        }

        $population->setSchedules($schedules);
        return $population;
    }

    private function crossoverSchedules(Schedule $s1, Schedule $s2): Schedule
    {
        $entries1 = $s1->getScheduleEntries();
        $entries2 = $s2->getScheduleEntries();
        $schedule = Schedule::generateRandomSchedule($this->data);
        $newEntries = $schedule->getScheduleEntries();

        foreach ($newEntries as $key => $_) {
            $newEntries[$key] = rand(0, 1)
                ? ($entries1[$key] ?? $entries2[$key])
                : ($entries2[$key] ?? $entries1[$key]);
        }

        $schedule->setScheduleEntries($newEntries);
        return $schedule;
    }

    private function mutateSchedule(Schedule $schedule): void
    {
        $original = $schedule->getScheduleEntries();
        $mutated = Schedule::generateRandomSchedule($this->data)->getScheduleEntries();

        foreach ($original as $key => $entry) {
            if (mt_rand() / mt_getrandmax() < $this->mutationRate && isset($mutated[$key])) {
                $original[$key] = $mutated[$key];
            }
        }

        $schedule->setScheduleEntries($original);
    }

    private function tournamentSelection(Population $pop): Schedule
    {
        $contestants = [];
        $schedules = $pop->getSchedules();

        for ($i = 0; $i < $this->tournamentSize; $i++) {
            $contestants[] = $schedules[array_rand($schedules)];
        }

        usort($contestants, fn($a, $b) => $b->getFitness() <=> $a->getFitness());
        return $contestants[0];
    }
}
