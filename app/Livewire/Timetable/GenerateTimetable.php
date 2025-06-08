<?php

namespace App\Livewire\Timetable;

use Livewire\Component;

class GenerateTimetable extends Component
{

    public $isGenerating = false;
    public $showSuccess = false;

    public function render()
    {
        return view('livewire.timetable.generate-timetable');
    }
}
