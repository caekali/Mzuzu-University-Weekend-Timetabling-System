<?php

namespace App\Livewire\Timetable;

use App\Models\Lecturer;
use App\Models\Programme;
use App\Models\ScheduleEntry;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class Timetable extends Component
{

    public $entries;
    public $timeSlots = [];
    public $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    public $selectedProgramme = '';
    public $selectedLecturer = '';
    public $programmes;
    public $lecturers;
    public $showFilters = false;

    public function mount()
    {
        $this->programmes = Programme::all();
        $this->lecturers = Lecturer::with('user')->get();
        // $this->lecturers->transform(function ($lecturer) {
        //     $data = [
        //         'id'    => $lecturer->id,
        //         'name'  => $lecturer->user->first_name . ' ' . $lecturer->user->last_name,
        //     ];
        //     return $data;
        // });

        $start = Carbon::createFromTime(8, 0);
        $end = Carbon::createFromTime(17, 0);
        while ($start < $end) {
            $slotStart = $start->copy();
            $slotEnd = $start->copy()->addHour();
            $this->timeSlots[] = [
                'start' => $slotStart->format('H:i:s'),
                'end' => $slotEnd->format('H:i:s')
            ];
            $start->addHour();
        }

        $this->loadEntries();
    }

    public function updatedSelectedProgramme()
    {
        $this->loadEntries();
    }

    public function updatedSelectedLecturer()
    {
        $this->loadEntries();
    }

    public function loadEntries()
    {
        $query = ScheduleEntry::with(['course', 'lecturer.user', 'venue']);

        if ($this->selectedProgramme) {
            $query->where('programme_id', $this->selectedProgramme);
        }

        if ($this->selectedLecturer) {
            $query->where('lecturer_id', $this->selectedLecturer);
        }

        $this->entries = $query->get();
    }

    #[On('refresh-list')]
    public function refresh() {}

    public function openModal($id = null, $day, $startTime, $endTime)
    {
        $this->dispatch('openModal', $id, $day, $startTime, $endTime)->to('timetable.schedule-modal');
    }
    public function render()
    {
        return view('livewire.timetable.timetable');
    }
}
