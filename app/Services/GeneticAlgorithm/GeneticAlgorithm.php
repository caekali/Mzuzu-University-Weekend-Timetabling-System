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

    public function evolve(Population $population, int $generation = 0): Population
    {
        $crossover = $this->generateCrossoverPopulation($population, $generation);
        return $this->applyMutation($crossover, $generation);
    }

    private function generateCrossoverPopulation(Population $pop, int $generation): Population
    {
        $newPop = new Population();

        for ($i = 0; $i < $this->eliteSchedules; $i++) {
            $newPop->addSchedule($pop->getSchedule($i)->copy());
        }

        while ($newPop->count() < $this->populationSize) {
            $parent1 = $this->tournamentSelection($pop);
            $parent2 = $this->tournamentSelection($pop);
            
            if (mt_rand() / mt_getrandmax() < $this->crossoverRate) {
                $child = $this->crossoverSchedules($parent1, $parent2);
            } else {
                $child = $parent1->copy();
            }

            $newPop->addSchedule($child);
        }

        return $newPop;
    }

    private function applyMutation(Population $population, int $generation): Population
    {
        $adaptiveMutationRate = min(0.5, $this->mutationRate + ($generation * 0.01));
        $schedules = $population->getSchedules();

        for ($i = $this->eliteSchedules; $i < $this->populationSize; $i++) {
            $original = $schedules[$i]->getScheduleEntries();
            $mutated = Schedule::generateRandomSchedule($this->data)->getScheduleEntries();

            foreach ($original as $key => $entry) {
                if (mt_rand() / mt_getrandmax() < $adaptiveMutationRate && isset($mutated[$key])) {
                    $original[$key] = $mutated[$key];
                }
            }

            $schedules[$i]->setScheduleEntries($original);
        }

        $population->setSchedules($schedules);
        return $population;
    }

    private function crossoverSchedules(Schedule $s1, Schedule $s2): Schedule
    {
        $entries1 = $s1->getScheduleEntries();
        $entries2 = $s2->getScheduleEntries();
        $newEntries = [];

        $i = 0;
        foreach ($entries1 as $key => $entry) {
            $newEntries[$key] = ($i++ % 2 == 0) ? ($entries1[$key] ?? $entries2[$key]) : ($entries2[$key] ?? $entries1[$key]);
        }

        $schedule = new Schedule($this->data['constraints'] ?? []);
        $schedule->setScheduleEntries($newEntries);
        return $schedule;
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
