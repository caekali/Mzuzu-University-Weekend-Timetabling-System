<?php

namespace App\Livewire\Timetable;

use App\Jobs\RunScheduleGeneration;
use App\Livewire\Forms\GAParametersForm;
use App\Models\GAParameter;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class GenerateTimetable extends Component
{
    use WireUiActions;

    public $progress = 0;
    public $currentGeneration = 0;
    public $currentFitness = 0.0;
    public $isDone = false;
    public bool $isPolling = false;

    public GAParametersForm $form;
    public array $originalData = [];

    public $lastUpdated = '';

    public function mount()
    {
        $params = GAParameter::getOrCreate();
        if ($params) {
            $this->form->population_size       = $params->population_size;
            $this->form->number_of_generations = $params->number_of_generations;
            $this->form->tournament_size       = $params->tournament_size;
            $this->form->mutation_rate         = $params->mutation_rate * 100;
            $this->form->crossover_rate        = $params->crossover_rate * 100;
            $this->form->elite_schedules = $params->elite_schedules;
            $this->lastUpdated = $params->last_updated;
        }
        // for tracking changes
        $this->originalData = $this->getCurrentFormData();
    }

    public function getCurrentFormData(): array
    {
        return [
            'population_size' => $this->form->population_size,
            'number_of_generations' => $this->form->number_of_generations,
            'tournament_size' => $this->form->tournament_size,
            'mutation_rate' => $this->form->mutation_rate,
            'crossover_rate' => $this->form->crossover_rate,
            'elite_schedules' => $this->form->elite_schedules,
        ];
    }

    // Check if form data has changed compared to original
    public function getHasChangesProperty(): bool
    {
        return $this->getCurrentFormData() !== $this->originalData;
    }

    public function startGeneration()
    {
        Cache::forget('schedule_generation_progress');
        $this->currentGeneration =  0;
        $this->currentFitness =  0;
        $this->progress = 0;
        $this->isDone = false;
        RunScheduleGeneration::dispatch();
        $this->isPolling = true;
    }
    public function pollProgress()
    {
        $scheduleGenerationProcess = Cache::get('schedule_generation_progress');
        if ($scheduleGenerationProcess) {
            $this->currentGeneration = $scheduleGenerationProcess['generation'] ?? 0;
            $this->currentFitness = $scheduleGenerationProcess['fitness'] ?? 0.0;
            $this->progress = $scheduleGenerationProcess['progress'] ?? 0;
            $this->isDone = $scheduleGenerationProcess['isDone'] ?? true;

            if ($this->isDone) {
                $this->isPolling = false;
            }
        }
    }


    public function updateGAParameter()
    {
        $this->form->save();
        $this->lastUpdated = GAParameter::latest('last_updated')->value('last_updated');
        $this->originalData = $this->getCurrentFormData();
        $this->notification()->success(
            'Updated',
            'Algorithm parameters updated successfully.'
        );
    }
    public function render()
    {
        return view('livewire.timetable.generate-timetable');
    }
}
