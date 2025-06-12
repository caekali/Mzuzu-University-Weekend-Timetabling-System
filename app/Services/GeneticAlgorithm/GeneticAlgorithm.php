<?php

namespace App\Services\GeneticAlgorithm;

class GeneticAlgorithm
{
    public function __construct(
        protected array $courses,
        protected array $venues,
        protected array $timeSlots,
        protected int $populationSize = 30,
        protected int $generations = 100,
        protected float $mutationRate = 0.1
    ) {}

    public function run(): Schedule
    {
        $population = Population::initialize(
            $this->populationSize,
            $this->courses,
            $this->venues,
            $this->timeSlots
        );

        for ($gen = 0; $gen < $this->generations; $gen++) {
            $newSchedules = [];
            $fittest = $population->getFittest();
            $newSchedules[] = $fittest;

            while (count($newSchedules) < $this->populationSize) {
                $parent1 = $population->selectParent();
                $parent2 = $population->selectParent();

                $child = $parent1->crossover($parent2);

                if (mt_rand() / mt_getrandmax() < $this->mutationRate) {
                    $child->mutate($this->venues, $this->timeSlots);
                }

                $child->calculateFitness();
                $newSchedules[] = $child;
            }

            $population = new Population($newSchedules);
        }

        return $population->getFittest();
    }
}
