<?php

namespace App\Livewire\Timetable;

use App\Models\ScheduleDay;
use App\Models\ScheduleEntry;
use Carbon\Carbon;
use Livewire\Component;

class PersonalTimetable extends Component
{
    public $days = [];
    public $entries = [];
    public $timeSlots = [];

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
        $this->loadEntries();
    }

    public function loadEntries()
    {
        $query = ScheduleEntry::with(['course', 'lecturer.user', 'venue']);

        $current_role = session('current_role');
       
        if ($current_role == 'Student') {
            $student = auth()->user()->student;
            $query->where('level', $student->level);
            $query->where('programme_id', $student->programme_id);
        } elseif ($current_role == 'Lecturer') {
            $lecturer = auth()->user()->lecturer;
            $query->where('lecturer_id', $lecturer->id);
        } else {
            abort(401);
        }
        $this->entries = $query->get();

    }

    public function render()
    {
        return view('livewire.timetable.personal-timetable');
    }
}
