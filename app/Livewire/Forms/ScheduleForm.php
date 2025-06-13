<?php

namespace App\Livewire\Forms;

use App\Models\ScheduleEntry;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ScheduleForm extends Form
{
    public $scheduleEntryId;

    #[Validate('required|exists:courses,id')]
    public $course_id;

    #[Validate('required|exists:programmes,id')]
    public $programme_id;

    #[Validate('required|exists:lecturers,id')]
    public $lecturer_id;

    #[Validate('required|exists:venues,id')]
    public $venue_id;

    #[Validate('required')]
    public $start_time;

    #[Validate('required')]
    public $end_time;

    #[Validate('required|string')]
    public $day;

    public function store()
    {
        $validated =  $this->validate();

        if (!$this->scheduleEntryId) {
            ScheduleEntry::create($validated);
        } else {
            ScheduleEntry::findOrFail($this->$this->scheduleEntryId)
                ->update($validated);
        }
        $this->reset();
    }
}
