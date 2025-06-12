<?php

namespace App\Services\GeneticAlgorithm;

class GeneticAlgorithm
{
    protected Population $population;

    public function __construct(
        protected array $courses,
        protected array $venues,
        protected array $timeSlots,
        protected int $populationSize = 50,
        protected int $numberOfGenerations = 1000,
        protected float $crossoverRate = 0.7,
        protected float $mutationRate = 0.1,
        protected float $tournamentSize = 3

    ) {}

    public function run(): Schedule
    {
        $this->population = $this->initializePopulation();
        for ($generation = 0; $generation < $this->numberOfGenerations; $generation++) {
            $this->population->setSchedules($this->evolvePopulation());
        }
        return $this->population->getFittest();
    }

    protected function initializePopulation(): Population
    {
        $schedules = [];
        for ($i = 0; $i < $this->populationSize; $i++) {
            $schedules[] = Schedule::generateRandomSchedule($this->courses, $this->venues, $this->timeSlots);
        }
        return new Population($schedules);
    }


    protected function evolvePopulation(): array
    {
        $newSchedules = [];
        while (count($newSchedules) < $this->populationSize) {
            $parent1 = $this->tournamentSelection();
            $parent2 = $this->tournamentSelection();

            if (mt_rand() / mt_getrandmax() < $this->crossoverRate) {
                [$child1, $child2] = $parent1->crossover($parent2);
            } else {
                $child1 = clone $parent1;
                $child2 = clone $parent2;
            }

            if (mt_rand() / mt_getrandmax() < $this->mutationRate) {
                $child1->mutate($this->venues, $this->timeSlots);
            }
            if (mt_rand() / mt_getrandmax() < $this->mutationRate) {
                $child2->mutate($this->venues, $this->timeSlots);
            }

            $newSchedules[] = $child1;
            $newSchedules[] = $child2;
        }

        return $newSchedules;
    }

    protected function tournamentSelection(): Schedule
    {
        $contestants = [];
        $pool = $this->population->getSchedules();

        for ($i = 0; $i < $this->tournamentSize; $i++) {
            $contestants[] = $pool[array_rand($pool)];
        }

        usort($contestants, fn($a, $b) => $b->fitness <=> $a->fitness);
        return $contestants[0];
    }
}
