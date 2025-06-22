<?php

namespace App\Livewire\Timetable;

use App\Models\ScheduleDay;
use App\Models\Lecturer;
use App\Models\Programme;
use App\Models\ScheduleVersion;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class FullTimetable extends Component
{

    use WireUiActions;

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

    public $publishedVersion;

    public int|null $selectedVersionId = null;


    public function mount()
    {
        $this->days = ScheduleDay::where('enabled', true)->pluck('name')->toArray();

        $start = Carbon::createFromTime(7, 45);
        $end = Carbon::createFromTime(18, 45);
        while ($start < $end) {
            $slotStart = $start->copy();
            $slotEnd = $start->copy()->addHour();
            $this->timeSlots[] = [
                'start' => $slotStart->format('H:i'),
                'end' => $slotEnd->format('H:i')
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

        $this->publishedVersion = ScheduleVersion::published()->first();
        $this->selectedVersionId = optional(ScheduleVersion::published()->first())->id;


        $this->loadEntries();
    }

    #[On('version-selected')]
    public function setSelectedVersion($id)
    {
        $this->selectedVersionId = $id;
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

    public function updatedSelectedVersionId()
    {
        $this->loadEntries();
    }

    // public function loadEntries()
    // {
    //     // Exit early if no filters are selected
    //     if (
    //         !$this->selectedLevel &&
    //         !$this->selectedLecturer &&
    //         !$this->selectedVenue &&
    //         !$this->selectedProgramme
    //     ) {
    //         $this->entries = collect();
    //         return;
    //     }

    //     $query = ScheduleEntry::with(['course', 'lecturer.user', 'venue']);

    //     if ($this->selectedLevel) {
    //         $query->where('level', $this->selectedLevel);
    //     }

    //     if ($this->selectedLecturer) {
    //         $query->where('lecturer_id', $this->selectedLecturer);
    //     }

    //     if ($this->selectedVenue) {
    //         $query->where('venue_id', $this->selectedVenue);
    //     }

    //     if ($this->selectedProgramme) {
    //         $query->where('programme_id', $this->selectedProgramme);
    //     }

    //     $this->entries = $query->get();
    // }
    public function loadEntries()
    {
        $version = $this->selectedVersionId
            ? ScheduleVersion::find($this->selectedVersionId)
            : $this->publishedVersion;

        if (!$version) {
            $this->entries = collect();
            return;
        }

        $query = $version->entries()->with(['course', 'lecturer.user', 'venue']);

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

        $entries = $query->get();

        // group to get unique dat + lecturer_id + course +id + start_time + end_time
        $grouped = $entries->groupBy(function ($entry) {
            return "{$entry->day}-{$entry->lecturer_id}-{$entry->course_id}-{$entry->start_time}-{$entry->end_time}";
        });

        $filteredEntries = [];
        foreach ($grouped as $group) {
            $first = $group->first();
            $filteredEntries[] = (object)[
                'id' => $first->id,
                'day' => $first->day,
                'start_time' => date('H:i', strtotime($first->start_time)),
                'end_time' => date('H:i', strtotime($first->end_time)),
                'level' => $first->level,
                'course_code' => $first->course->code ?? '',
                'course_name' => $first->course->name ?? '',
                'lecturer' => optional($first->lecturer?->user)->first_name . ' ' . optional($first->lecturer?->user)->last_name,
                'venue' => $first->venue->name ?? '',
            ];
        }
        $this->entries = collect($filteredEntries);
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
