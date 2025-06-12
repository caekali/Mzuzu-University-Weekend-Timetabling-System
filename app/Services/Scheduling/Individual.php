<?php

namespace App\Services\Scheduling;

class Individual
{
    public Schedule $schedule;
    public float $fitness = 0.0;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
        $this->evaluateFitness();
    }

    public function evaluateFitness(): void
    {
        $evaluator = new FitnessEvaluator();
        $this->fitness = $evaluator->evaluate($this->schedule);
    }
}
