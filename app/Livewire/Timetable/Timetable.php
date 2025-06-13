<?php

namespace App\Livewire\Timetable;

use App\Models\Lecturer;
use App\Models\Programme;
use App\Models\ScheduleEntry;
use App\Models\Venue;
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
    public $selectedVenue = '';

    public $programmes;
    public $lecturers;
    public $venues;

    public function mount()
    {
        $this->programmes = Programme::all();
        $this->venues = Venue::all();

        $this->lecturers = Lecturer::with('user')->get()->map(function ($lecturer) {
            return [
                'id' => $lecturer->id,
                'name' => $lecturer->user->first_name . ' ' . $lecturer->user->last_name,
            ];
        });

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

    public function updatedSelectedVenue()
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

        if ($this->selectedVenue) {
            $query->where('venue_id', $this->selectedVenue);
        }

        $this->entries = $query->get();
    }

    #[On('refresh-list')]
    public function refresh()
    {
        $this->loadEntries();
    }

    public function openModal($id = null, $day, $startTime, $endTime)
    {
        $this->dispatch('openModal', $id, $day, $startTime, $endTime)->to('timetable.schedule-modal');
    }
    public function render()
    {
        return view('livewire.timetable.timetable');
    }
}
