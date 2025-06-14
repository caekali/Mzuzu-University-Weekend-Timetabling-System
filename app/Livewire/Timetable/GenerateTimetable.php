<?php

namespace App\Livewire\Timetable;

use App\Jobs\RunScheduleGeneration;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class GenerateTimetable extends Component
{
    public $progress = 0;
    public $currentGeneration = 0;
    public $currentFitness = 0.0;
    public $totalGenerations = 100;
    public $isDone = false;

    public function startGeneration()
    {
        Cache::forget('schedule_generation_progress');
        $this->currentGeneration =  0;
        $this->currentFitness =  0;
        $this->progress = 0;
        $this->isDone = false;

        RunScheduleGeneration::dispatch($this->totalGenerations);
    }
    public function pollProgress()
    {
        $scheduleGenerationProcess = Cache::get('schedule_generation_progress');
        if ($scheduleGenerationProcess) {
            $this->currentGeneration = $scheduleGenerationProcess['generation'] ?? 0;
            $this->currentFitness = $scheduleGenerationProcess['fitness'] ?? 0.0;
            $this->progress = $scheduleGenerationProcess['progress'] ?? 0;
            $this->isDone = $scheduleGenerationProcess['is_done'] ?? false;
        }
    }
    public function render()
    {
        return view('livewire.timetable.generate-timetable');
    }
}
