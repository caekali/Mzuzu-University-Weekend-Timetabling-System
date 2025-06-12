<?php

namespace App\Services\GeneticAlgorithm;

class Population
{
    public array $schedules = [];

    public function __construct(array $schedules = [])
    {
        $this->schedules = $schedules;
    }

    public static function initialize(int $size, array $courses, array $venues, array $timeSlots): self
    {
        $chromosomes = [];
        for ($i = 0; $i < $size; $i++) {
            $chromosomes[] = Schedule::random($courses, $venues, $timeSlots);
        }

        return new self($chromosomes);
    }

    public function getFittest(FitnessEvaluator $evaluator): Schedule
    {
        usort($this->schedules, function ($a, $b) use ($evaluator) {
            return $b->calculateFitness($evaluator) <=> $a->calculateFitness($evaluator);
        });

        return $this->schedules[0];
    }

    public function selectParent(): Schedule
    {
        // Tournament selection (pick 3 random and choose the best)
        $sample = array_rand($this->schedules, 3);
        $candidates = array_map(fn($i) => $this->schedules[$i], $sample);

        usort($candidates, fn($a, $b) => $b->fitness <=> $a->fitness);

        return $candidates[0];
    }
}
