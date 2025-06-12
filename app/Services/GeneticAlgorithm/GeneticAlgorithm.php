<?php

namespace App\Services\GeneticAlgorithm;


class GeneticAlgorithm
{
    protected Population $population;

    protected int $populationSize = 50;
    protected int $tournamentSize = 5;
    protected int $generations = 1000;

    protected float $crossoverRate;
    protected float $mutationRate;

    protected array $courses;
    protected array $venues;
    protected array $timeSlots;

    public function __construct(
        array $courses,
        array $venues,
        array $timeSlots,
        float $crossoverRate = 0.8,
        float $mutationRate = 0.1,
        int $populationSize = 50,
        int $tournamentSize = 5,
        int $generations = 100
    ) {
        $this->courses = $courses;
        $this->venues = $venues;
        $this->timeSlots = $timeSlots;
        $this->crossoverRate = $crossoverRate;
        $this->mutationRate = $mutationRate;
        $this->populationSize = $populationSize;
        $this->tournamentSize = $tournamentSize;
        $this->generations = $generations;
    }

    public function run(): Schedule
    {
        // Initialize population (Population object)
        $this->population = Population::initialize($this->populationSize, $this->courses, $this->venues, $this->timeSlots);

        for ($gen = 0; $gen < $this->generations; $gen++) {
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

            // Replace old population schedules with new generation
            $this->population->setSchedules($newSchedules);
        }

        return $this->getBestSchedule();
    }

    protected function tournamentSelection(): Schedule
    {
        $schedules = $this->population->all();

        $tournament = [];
        for ($i = 0; $i < $this->tournamentSize; $i++) {
            $tournament[] = $schedules[rand(0, count($schedules) - 1)];
        }

        usort($tournament, fn(Schedule $a, Schedule $b) => $b->fitness <=> $a->fitness);

        return $tournament[0];
    }

    protected function getBestSchedule(): Schedule
    {
        $schedules = $this->population->all();

        usort($schedules, fn(Schedule $a, Schedule $b) => $b->fitness <=> $a->fitness);

        return $schedules[0];
    }
}
