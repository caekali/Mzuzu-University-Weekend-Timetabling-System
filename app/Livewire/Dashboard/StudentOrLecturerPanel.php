<?php

namespace App\Livewire\Dashboard;

use App\Models\ScheduleEntry;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StudentOrLecturerPanel extends Component
{
    public $todayScheduleEntries = [];

    public function mount()
    {
        $this->todayScheduleEntries = ScheduleEntry::with(['course', 'lecturer.user', 'venue'])
            ->when(
                Auth::user()->lecturer,
                fn($query) =>
                $query->where('lecturer_id', Auth::user()->lecturer->id)
            )
            ->when(
                Auth::user()->student,
                fn($query) =>
                $query->where('programme_id', Auth::user()->student->programme_id)
            )
            // ->where('day', now()->format('l'))
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.student-or-lecturer-panel');
    }
}
