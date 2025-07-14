<?php

namespace App\Livewire\Timetable;

use App\Exports\ScheduleExport;
use App\Models\ScheduleDay;
use App\Models\Lecturer;
use App\Models\Programme;
use App\Models\ScheduleVersion;
use App\Models\Venue;
use App\Services\GeneticAlgorithm\ConstraintViolationChecker;
use App\Services\GeneticAlgorithm\GADataLoaderService;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
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

    public $currentVersion;

    public int|null $selectedVersionId = null;

    public bool $showFilters = false;

    public function mount()
    {
        $this->days = ScheduleDay::where('enabled', true)->pluck('name')->sort()->toArray();
        $loader = new GADataLoaderService();
        $slots = $loader->generateTimeslots();

        // Collect all unique start and end times
        $this->timeSlots = collect($slots)
            ->map(fn($slot) => [
                'range' => $slot['start'] . ' - ' . $slot['end'],
                'start' => $slot['start'],
                'end' => $slot['end'],
                'type' => $slot['type']
            ])
            ->unique('range')
            ->sortBy('start')
            ->values()
            ->all();

        $this->levels = DB::table('schedule_entries')
            ->whereNotNull('level')
            ->distinct()
            ->pluck('level')
            ->sort();


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

        $this->currentVersion = ScheduleVersion::published()->first();

        $this->selectedVersionId = $this->currentVersion ?   $this->currentVersion->id : ScheduleVersion::first()?->id;


        $this->entries = collect();
        // $this->loadEntries();
    }

    #[On('version-selected')]
    public function setSelectedVersion($id)
    {
        $this->selectedVersionId = $id;
        $this->loadEntries();
    }

    #[On('refresh-list')]
    public function refresh()
    {
        $this->loadEntries();
    }

    #[On('update-current')]
    public function updateCurrentVersion()
    {
        $this->currentVersion = ScheduleVersion::published()->first();
    }

    // #[On('version-deleted')]
    // public function resetTimetable($id)
    // {
    //     dd($id, $this->selectedVersionId);


    //     if ($this->selectedVersionId == $id) {

    //         $this->entries = collect();
    //         $this->currentVersion = null;
    //         $this->selectedVersionId = null;
    //         // $this->currentVersion = ScheduleVersion::published()->first() ?? ScheduleVersion::first();
    //         // $this->selectedVersionId = $this->currentVersion?->id;
    //         // $this->loadEntries();
    //     }
    // }

    #[On('selected-version-deleted')]
    public function resetTimetable()
    {
        $fallback = ScheduleVersion::published()->first() ?? ScheduleVersion::first();

        if ($fallback) {
            $this->selectedVersionId = $fallback->id;
            $this->currentVersion = $fallback;
            $this->loadEntries();
        } else {
            $this->selectedVersionId = null;
            $this->currentVersion = null;
            $this->entries = collect();
        }
    }

    public function loadEntries()
    {
        $version = $this->selectedVersionId
            ? ScheduleVersion::find($this->selectedVersionId)
            : $this->currentVersion;

        if (!$version) {
            $this->entries = collect();
            return;
        }

        $this->currentVersion = $version;

        $query = $version->entries()->with(['course', 'lecturer.user', 'venue']);

        if ($this->selectedLevel) $query->where('level', $this->selectedLevel);
        if ($this->selectedLecturer) $query->where('lecturer_id', $this->selectedLecturer);
        if ($this->selectedVenue) $query->where('venue_id', $this->selectedVenue);
        if ($this->selectedProgramme) $query->where('programme_id', $this->selectedProgramme);

        $entries = $query->get();

        $violations = (new ConstraintViolationChecker())->checkMany($entries);


        $entryIds = collect($violations)->flatMap(fn($v) => $v['entry_ids'] ?? [])->map(fn($id) => (int) $id)->unique();
        $conflictIds = collect($violations)->flatMap(fn($v) => $v['conflicting_entry_ids'] ?? [])->map(fn($id) => (int) $id)->unique();

        $allConflictIds = $entryIds->merge($conflictIds)->unique();

        $this->dispatch('highlight-conflicts', [
            'entryIds' => $allConflictIds->values()->all(),
        ]);

        $grouped = $entries->groupBy(fn($e) => "{$e->day}-{$e->lecturer_id}-{$e->course_id}-{$e->start_time}-{$e->end_time}");
        $filteredEntries = $grouped->map(function ($group) {
            $first = $group->first();
            return (object)[
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
        })->values();

        $this->entries = collect($filteredEntries);
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

    public function openModal($id = null, $day = null, $startTime = null, $endTime = null)
    {
        $this->dispatch('openModal', $this->selectedVersionId, $id, $day, $startTime, $endTime)->to('timetable.schedule-modal');
    }

    public function export($format)
    {
        if ($format === 'excel') {
            return Excel::download(new ScheduleExport, 'timetable.xlsx');
        }

        // if ($format === 'pdf') {
        //     $data = [...]; // fetch your data
        //     $pdf = Pdf::loadView('exports.timetable-pdf', ['data' => $data]);
        //     return response()->streamDownload(fn () => print($pdf->output()), 'timetable.pdf');
        // }
    }

    public function render()
    {
        return view('livewire.timetable.full-timetable');
    }
}
