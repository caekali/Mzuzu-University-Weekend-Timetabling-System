<?php

namespace App\Livewire\Dashboard;

use App\Models\ScheduleDay;
use App\Models\ScheduleVersion;
use Carbon\Carbon;
use Livewire\Component;

class StudentOrLecturerPanel extends Component
{
    public $publishedVersion;

    public $days;

    public $selectedDay;

    public $allDayEntries = [];

    public function mount()
    {
        $this->days = ScheduleDay::where('enabled', true)->pluck('name')->sort()->toArray();

        $today = Carbon::now()->format('l');

        $this->selectedDay = in_array($today, $this->days) ? $today : reset($this->days);

        $this->publishedVersion = ScheduleVersion::published()->first();

        if ($this->publishedVersion)
            $this->loadEntriesForDay($this->selectedDay);
    }

    public function updatedSelectedDay($value)
    {
        $this->loadEntriesForDay($value);
    }

    public function loadEntriesForDay($day)
    {
        $this->selectedDay = $day;

        $entries = $this->publishedVersion->entries()
            ->with(['course', 'lecturer.user', 'venue'])
            ->where('day', $day)
            ->when(
                auth()->user()->lecturer,
                fn($q) => $q->where('lecturer_id', auth()->user()->lecturer->id)
            )
            ->when(
                auth()->user()->student,
                fn($q) => $q->where('programme_id', auth()->user()->student->programme_id)
                    ->where('level', auth()->user()->student->level)
            )
            ->orderBy('start_time')
            ->get();

        $grouped = $entries->groupBy(fn($entry) => "{$entry->day}-{$entry->course_id}-{$entry->lecturer_id}");

        $this->allDayEntries = [];

        foreach ($grouped as $blocks) {
            $blocks = $blocks->sortBy('start_time')->values();
            $current = $blocks[0];

            for ($i = 1; $i < $blocks->count(); $i++) {
                $next = $blocks[$i];
                if ($next->start_time <= $current->end_time) {
                    $current->end_time = max($current->end_time, $next->end_time);
                } else {
                    $this->allDayEntries[] = $this->formatEntry($current);
                    $current = $next;
                }
            }

            $this->allDayEntries[] = $this->formatEntry($current);
        }
    }

    protected function formatEntry($entry)
    {
        return [
            'day' => $entry->day,
            'start_time' => date('H:i', strtotime($entry->start_time)),
            'end_time' => date('H:i', strtotime($entry->end_time)),
            'level' => $entry->level,
            'course_code' => $entry->course->code ?? '',
            'course_name' => $entry->course->name ?? '',
            'lecturer' => $entry->lecturer->user->first_name . ' ' . $entry->lecturer->user->last_name ?? '',
            'venue' => $entry->venue->name ?? '',
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.student-or-lecturer-panel');
    }
}
