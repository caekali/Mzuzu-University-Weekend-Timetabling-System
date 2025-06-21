<?php

namespace App\Livewire\Dashboard;

use App\Models\ScheduleEntry;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StudentOrLecturerPanel extends Component
{
    public $toscheduleDayEntries = [];

    public function mount()
    {
        $entries = ScheduleEntry::with(['course', 'lecturer.user', 'venue'])
            ->when(
                Auth::user()->lecturer,
                fn($query) =>
                $query->where('lecturer_id', Auth::user()->lecturer->id)
            )
            ->when(
                Auth::user()->student,
                fn($query) =>
                $query->where('programme_id', Auth::user()->student->programme_id)
                    ->where('level', Auth::user()->student->level)


            )
            ->where('day', now()->format('l'))
            ->orderBy('start_time')
            ->get();

        $grouped = $entries->groupBy(function ($entry) {
            return "{$entry->day}-{$entry->course_id}-{$entry->lecturer_id}";
        });

        $mergedEntries = [];

        foreach ($grouped as $blocks) {
            $blocks = $blocks->sortBy('start_time')->values();

            $current = $blocks[0];

            for ($i = 1; $i < $blocks->count(); $i++) {
                $next = $blocks[$i];

                if ($next->start_time <= $current->end_time) {
                    $current->end_time = max($current->end_time, $next->end_time);
                } else {
                    $mergedEntries[] = [
                        'day' => $current->day,
                        'start_time' => $current->start_time,
                        'end_time' => $current->end_time,
                        'level' => $current->level,
                        'course' => $current->course->name ?? '',
                        'lecturer' => $current->lecturer->user->name ?? '',
                        'venue' => $current->venue->name ?? '',
                    ];
                    $current = $next;
                }
            }

            // Push last one
            $this->toscheduleDayEntries[] = [
                'day' => $current->day,
                'start_time' => date('H:i', strtotime($current->start_time)),
                'end_time' => date('H:i', strtotime($current->end_time)),
                'level' => $current->level,
                'course_code' => $current->course->code ?? '',
                'course_name' => $current->course->name ?? '',
                'lecturer' => $current->lecturer->user->first_name . ' ' . $current->lecturer->user->last_name  ?? '',
                'venue' => $current->venue->name ?? '',
            ];
        }
    }

    public function render()
    {
        return view('livewire.dashboard.student-or-lecturer-panel');
    }
}
