<?php

namespace App\Services\GA;

class GeneticAlgorithm
{
   protected array $population = [];
    protected int $populationSize;
    protected int $numberOfGenerations;
    protected float $mutationRate;
    protected float $crossoverRate;
    protected int $tournamentSize;
    protected array $venues;
    protected array $courses;


    public function __construct()
    {
        $this->populationSize = 50;
        $this->numberOfGenerations = 100;
        $this->mutationRate = 0.01;
        $this->crossoverRate = 0.7;
        $this->tournamentSize = 5;

        $this->venues = Venue::all()->keyBy('id')->toArray();
        $this->courses = Course::with(['allocations.lecturer', 'programmes'])->get()->toArray();
    }

    public function run(): array
    {
        $this->initializePopulation();

        for ($generation = 0; $generation < $this->numberOfGenerations; $generation++) {
            $this->population = $this->evolvePopulation();
        }

        return $this->getFittestIndividual();
    }


    protected function initializePopulation(): void
    {
        for ($i = 0; $i < $this->populationSize; $i++) {
            $schedule = Schedule::generateRandom($this->courses, $this->venues);
            $this->population[] = $schedule;
        }
    }

    protected function evolvePopulation(): array
    {
        $newPopulation = [];

        while (count($newPopulation) < $this->populationSize) {
            $parent1 = $this->tournamentSelection();
            $parent2 = $this->tournamentSelection();

            if (mt_rand() / mt_getrandmax() < $this->crossoverRate) {
                $offspring = $parent1->crossover($parent2);
            } else {
                $offspring = clone $parent1;
            }

            $offspring->mutate($this->mutationRate, $this->venues);
            $newPopulation[] = $offspring;
        }

        return $newPopulation;
    }

    protected function tournamentSelection(): Schedule
    {
        $tournament = [];

        for ($i = 0; $i < $this->tournamentSize; $i++) {
            $randomIndex = array_rand($this->population);
            $tournament[] = $this->population[$randomIndex];
        }

        usort($tournament, fn($a, $b) => $b->fitness() <=> $a->fitness());
        return $tournament[0];
    }

     protected function getFittestIndividual(): array
    {
        usort($this->population, fn($a, $b) => $b->fitness() <=> $a->fitness());
        return $this->population[0]->toArray();
    }
}
