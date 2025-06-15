<?php

namespace App\DTO;

use App\Models\GAParameter;

class GAParameterDTO
{
    public int $populationSize;
    public int $numberOfGenerations;
    public int $tournamentSize;
    public float $mutationRate;
    public float $crossoverRate;

    public function __construct(GAParameter $param)
    {
        $this->populationSize       = max(1, (int)$param->population_size);
        $this->numberOfGenerations  = max(1, (int)$param->number_of_generations);
        $this->tournamentSize       = max(1, (int)$param->tournament_size);
        $this->mutationRate         = max(0.0, min(1.0, (float)$param->mutation_rate));
        $this->crossoverRate        = max(0.0, min(1.0, (float)$param->crossover_rate));
    }

    public static function fromDb(): self
    {
        return new self(GAParameter::getOrCreate());
    }
}
