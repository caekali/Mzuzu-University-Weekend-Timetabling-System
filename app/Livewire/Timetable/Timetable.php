<?php

namespace App\Livewire\Timetable;

use Livewire\Component;

class Timetable extends Component
{

    public function openModal($id = null, $day, $startTime, $endTime)
    {
        $this->dispatch('openModal', $id, $day, $startTime, $endTime)->to('timetable.schedule-modal');
    }
    public function render()
    {
        return view('livewire.timetable.timetable');
    }
}
