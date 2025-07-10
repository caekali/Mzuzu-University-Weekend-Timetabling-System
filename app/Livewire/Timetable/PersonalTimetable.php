<?php

namespace App\Livewire\Timetable;

use App\Models\ScheduleDay;
use App\Models\ScheduleVersion;
use App\Services\GeneticAlgorithm\GADataLoaderService;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;


use function App\Helpers\getSetting;

class PersonalTimetable extends Component
{
    public $days = [];
    public $entries = [];
    public $timeSlots = [];
    public $publishedVersion = null;

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
            ])
            ->unique('range')
            ->sortBy('start')
            ->values()
            ->all();

        $this->publishedVersion = ScheduleVersion::published()->first();
        $this->loadEntries();
    }

    public function loadEntries()
    {
        $user = Auth::user();
        if ($this->publishedVersion) {
            $query = $this->publishedVersion->entries()->with(['course', 'venue', 'lecturer.user']);
            $current_role = session('current_role');

            if ($current_role == 'Student') {
                $student = $user->student;
                $query->where('level', $student->level)
                    ->where('programme_id', $student->programme_id);
            } elseif ($current_role == 'Lecturer') {
                $lecturer = $user->lecturer;
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

    public function exportToPdf()
    {


        $title = '';
        if ($this->publishedVersion) {
            $published_at = $this->publishedVersion->published_at;
            $title = $this->publishedVersion->label . " Mzuzu University ICT Weekend Teaching Timetable $published_at";
        }

        $data = [
            'title' => $title,
            'entries' => $this->entries,
            'timeSlots' => $this->timeSlots,
            'days' => $this->days,
            'subtitle' => '',
        ];

        $user = Auth::user();
        if (session('current_role') === 'Student' && $user->student) {
            $student = $user->student;
            $data['subtitle'] = "For " . optional($student->programme)->name . " Level " . $student->level;
        }

        if (session('current_role') === 'Lecturer' && $user->lecturer) {
            $data['subtitle'] = "For {$user->first_name} {$user->last_name}";
        }

        $customPaper = [0, 0, 850.00, 1000.00];

        $pdf = PDF::loadView('exports.timetable', $data)
            ->setPaper($customPaper, 'landscape');

        $filename = 'ict_weekend_timetable_' . optional($this->publishedVersion)->published_at . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename);
    }




    public function render()
    {
        return view('livewire.timetable.personal-timetable');
    }
}
