<?php

namespace App\Livewire\Timetable;

use App\Models\ScheduleDay;
use App\Models\Lecturer;
use App\Models\Programme;
use App\Models\ScheduleEntry;
use App\Models\Venue;
use App\Services\GeneticAlgorithm\GADataLoaderService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class FullTimetable extends Component
{

    public $entries = [];

    public $timeSlots = [];

    public $days = [];

    public $levels = [];

    public $selectedLevel;

    public $selectedLecturer;

    public $selectedVenue;

    public $selectedProgramme;

    public $programmes;

    public $lecturers;

    public $venues;

    public function mount()
    {
        $this->days = ScheduleDay::where('enabled', true)->pluck('name')->toArray();

        $start = Carbon::createFromTime(7, 45);
        $end = Carbon::createFromTime(18, 45);
        while ($start < $end) {
            $slotStart = $start->copy();
            $slotEnd = $start->copy()->addHour();
            $this->timeSlots[] = [
                'start' => $slotStart->format('H:i:s'),
                'end' => $slotEnd->format('H:i:s')
            ];
            $start->addHour();
        }

        $this->levels = DB::table('schedule_entries')
            ->whereNotNull('level')
            ->distinct()
            ->pluck('level');


        $this->programmes = Programme::all()->map(function ($programme) {
            return [
                'id' => $programme->id,
                'name' => $programme->name,
            ];
        });


        $this->venues = Venue::all();

        $this->lecturers = Lecturer::with('user')->get()->map(function ($lecturer) {
            return [
                'id' => $lecturer->id,
                'name' => $lecturer->user->first_name . ' ' . $lecturer->user->last_name,
            ];
        });

        $this->loadEntries();
    }

    public function updatedSelectedLevel()
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

    public function updatedSelectedProgramme()
    {
        $this->loadEntries();
    }


public function loadEntries()
{
    // Exit early if no filters are selected
    if (
        !$this->selectedLevel &&
        !$this->selectedLecturer &&
        !$this->selectedVenue &&
        !$this->selectedProgramme
    ) {
        $this->entries = collect(); // empty collection
        return;
    }

    $query = ScheduleEntry::with(['course', 'lecturer.user', 'venue']);

    if ($this->selectedLevel) {
        $query->where('level', $this->selectedLevel);
    }

    if ($this->selectedLecturer) {
        $query->where('lecturer_id', $this->selectedLecturer);
    }

    if ($this->selectedVenue) {
        $query->where('venue_id', $this->selectedVenue);
    }

    if ($this->selectedProgramme) {
        $query->where('programme_id', $this->selectedProgramme);
    }

    $this->entries = $query->get();
}


    #[On('refresh-list')]
    public function refresh()
    {
        $this->loadEntries();
    }

    public function openModal($id = null, $day = null, $startTime = null, $endTime = null)
    {
        $this->dispatch('openModal', $id, $day, $startTime, $endTime)->to('timetable.schedule-modal');
    }
    public function render()
    {
        return view('livewire.timetable.full-timetable');
    }
}
