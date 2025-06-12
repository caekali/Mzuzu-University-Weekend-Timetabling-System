<?php

namespace App\Services\GeneticAlgorithm;

class GeneticAlgorithm
{
    protected int $populationSize;
    protected int $maxGenerations;
    protected float $mutationRate;

    protected array $courses;
    protected array $venues;
    protected array $timeSlots;

    protected FitnessEvaluator $evaluator;

    public function __construct(
        array $courses,
        array $venues,
        array $timeSlots,
        int $populationSize = 50,
        int $maxGenerations = 100,
        float $mutationRate = 0.1
    ) {
        $this->courses = $courses;
        $this->venues = $venues;
        $this->timeSlots = $timeSlots;

        $this->populationSize = $populationSize;
        $this->maxGenerations = $maxGenerations;
        $this->mutationRate = $mutationRate;

        $this->evaluator = new FitnessEvaluator();
    }

    public function run(): Schedule
    {
        $population = Population::initialize(
            $this->populationSize,
            $this->courses,
            $this->venues,
            $this->timeSlots
        );

        $best = $population->getFittest($this->evaluator);

        for ($generation = 0; $generation < $this->maxGenerations; $generation++) {
            $newChromosomes = [];

            // Elitism: carry the best forward
            $newChromosomes[] = $best;

            while (count($newChromosomes) < $this->populationSize) {
                $parent1 = $population->selectParent();
                $parent2 = $population->selectParent();

                $child = $parent1->crossover($parent2);

                if (mt_rand() / mt_getrandmax() < $this->mutationRate) {
                    $child->mutate($this->venues, $this->timeSlots);
                }

                $child->calculateFitness($this->evaluator);
                $newChromosomes[] = $child;
            }

            $population = new Population($newChromosomes);
            $best = $population->getFittest($this->evaluator);

            // Optional: log progress
            logger("Generation $generation - Fitness: " . $best->fitness);
        }

        return $best;
    }



    
}
