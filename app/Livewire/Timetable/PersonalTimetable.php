<?php

namespace App\Livewire\Timetable;

use App\Models\ScheduleDay;
use App\Models\ScheduleVersion;
use Carbon\Carbon;
use Livewire\Component;

use function App\Helpers\getSetting;

class PersonalTimetable extends Component
{
    public $days = [];
    public $entries = [];
    public $timeSlots = [];
    public $publishedVersion = null;

    public function mount()
    {
        $this->days = ScheduleDay::where('enabled', true)->pluck('name')->toArray();

        // HH:MM
        [$sh, $sm] = explode(':', getSetting('start_time', '07:00'));
        [$eh, $em] = explode(':', getSetting('end_time', '07:00'));

        $start = Carbon::createFromTime($sh, $sm);
        $end = Carbon::createFromTime($eh, $em);
        while ($start < $end) {
            $slotStart = $start->copy();
            $slotEnd = $start->copy()->addMinutes(intval(getSetting('slot_duration', 60)));
            $this->timeSlots[] = [
                'start' => $slotStart->format('H:i'),
                'end' => $slotEnd->format('H:i')
            ];
            $start->addMinutes(intval(getSetting('slot_duration', 60)));
        }

        $this->publishedVersion = ScheduleVersion::published()->first();
        $this->loadEntries();
    }

    public function loadEntries()
    {
        if ($this->publishedVersion) {
            $query = $this->publishedVersion->entries()->with(['course', 'venue', 'lecturer.user']);
            $current_role = session('current_role');

            if ($current_role == 'Student') {
                $student = auth()->user()->student;
                $query->where('level', $student->level)
                    ->where('programme_id', $student->programme_id);
            } elseif ($current_role == 'Lecturer') {
                $lecturer = auth()->user()->lecturer;
                $query->where('lecturer_id', $lecturer->id);
            } else {
                abort(401);
            }

            $entries = $query->get();

            // group to get unique dat + lecturer_id + course +id + start_time + end_time
            $grouped = $entries->groupBy(function ($entry) {
                return "{$entry->day}-{$entry->lecturer_id}-{$entry->course_id}-{$entry->start_time}-{$entry->end_time}";
            });

            $filteredEntries = [];
            foreach ($grouped as $group) {
                $first = $group->first();
                $filteredEntries[] = [
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
        } else {
            $this->entries = collect();
        }
    }

    public function render()
    {
        return view('livewire.timetable.personal-timetable');
    }
}
