<?php

namespace App\Services\GeneticAlgorithm;

class ScheduleService
{
    protected Population $population;

    protected int $populationSize;
    protected int $numberOfGenerations;
    protected int $tournamentSize;
    protected float $mutationRate;
    protected float $crossoverRate;
    protected array $courses;
    protected array $venues;
    protected array $timeslots;

    public function __construct(
        int $populationSize,
        int $numberOfGenerations,
        int $tournamentSize,
        float $mutationRate,
        float $crossoverRate,
        array $courses,
        array $venues,
        array $timeslots
    ) {
        $this->populationSize = $populationSize;
        $this->numberOfGenerations = $numberOfGenerations;
        $this->tournamentSize = $tournamentSize;
        $this->mutationRate = $mutationRate;
        $this->crossoverRate = $crossoverRate;
        $this->courses = $courses;
        $this->venues = $venues;
        $this->timeslots = $timeslots;

        $this->population = $this->initializePopulation();
    }

    public function initializePopulation(): Population
    {
        $schedules = [];
        for ($i = 0; $i < $this->populationSize; $i++) {
            $schedules[] = Schedule::generateRandomSchedule($this->courses, $this->venues, $this->timeslots);
        }
        return new Population($schedules);
    }


    public function evolvePopulation()
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
                $child1->mutate($this->venues, $this->timeslots);
            }
            if (mt_rand() / mt_getrandmax() < $this->mutationRate) {
                $child2->mutate($this->venues, $this->timeslots);
            }

            $newSchedules[] = $child1;
            $newSchedules[] = $child2;
        }
        $this->population->setSchedules($newSchedules);
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

    public function getBest(): Schedule
    {
        return $this->population->getFittest();
    }
}
