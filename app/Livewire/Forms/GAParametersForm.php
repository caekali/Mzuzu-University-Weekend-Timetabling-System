<?php

namespace App\Livewire\Forms;

use App\Models\GAParameter;
use Livewire\Attributes\Validate;
use Livewire\Form;

class GAParametersForm extends Form
{
    #[Validate('required|integer|min:1')]
    public $population_size = '';

    #[Validate('required|integer|min:1')]
    public $number_of_generations = '';

    #[Validate('required|integer|min:1')]
    public $tournament_size = '';

    #[Validate('required|numeric|min:0|max:100')]
    public $mutation_rate = '';

    #[Validate('required|numeric|min:0|max:100')]
    public $crossover_rate = '';

    public function save()
    {
        $this->validate();
        $mutationRateFloat = $this->mutation_rate / 100;
        $crossoverRateFloat = $this->crossover_rate / 100;
        GAParameter::first()->update([
            'population_size'       => $this->population_size,
            'number_of_generations' => $this->number_of_generations,
            'tournament_size'       => $this->tournament_size,
            'mutation_rate'         => $mutationRateFloat,
            'crossover_rate'        => $crossoverRateFloat,
        ]);
    }
}
