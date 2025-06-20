<?php

namespace App\Livewire\Dashboard;

use App\Models\ScheduleEntry;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LecturerPanel extends Component
{

    public $toscheduleDayEntries = [];

    public function mount()
    {
        $entries = ScheduleEntry::with(['course', 'lecturer.user', 'venue'])
            ->when(Auth::user()->lecturer, function ($query) {
                return $query->where('lecturer_id', Auth::user()->lecturer->id);
            })
            ->when(Auth::user()->student, function ($query) {
                return $query->where('programme_id', Auth::user()->student->programme_id);
            })
            ->where('day', now()->format('l'))
            ->get();

        $this->toscheduleDayEntries = $entries->groupBy(function ($entry) {
            return implode('-', [
                $entry->lecturer_id,
                $entry->level,
                $entry->course_id,
                $entry->start_time,
                $entry->end_time,
            ]);
        })->first();
    }

    public function render()
    {
        return view('livewire.dashboard.lecturer-panel');
    }
}
