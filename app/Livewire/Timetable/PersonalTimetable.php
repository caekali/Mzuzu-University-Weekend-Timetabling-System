<?php

namespace App\Livewire\Timetable;

use App\Models\ScheduleDay;
use App\Models\ScheduleVersion;
use App\Services\GeneticAlgorithm\GADataLoaderService;
use Carbon\Carbon;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Exports\PersonalTimetableExport;
use Maatwebsite\Excel\Facades\Excel;


if (class_exists('Maatwebsite\\Excel\\Facades\\Excel')) {
    class_alias('Maatwebsite\\Excel\\Facades\\Excel', 'ExcelFacadeAlias');
    if (interface_exists('Maatwebsite\\Excel\\Concerns\\FromArray')) {
        class_alias('Maatwebsite\\Excel\\Concerns\\FromArray', 'FromArrayAlias');
    }
    if (interface_exists('Maatwebsite\\Excel\\Concerns\\WithHeadings')) {
        class_alias('Maatwebsite\\Excel\\Concerns\\WithHeadings', 'WithHeadingsAlias');
    }
}

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
        $user = Auth::user();
        $data = [
            'entries' => $this->entries,
            'timeSlots' => $this->timeSlots,
            'days' => $this->days,
            'published_at' => $this->publishedVersion->published_at
        ];

        // Add programme name if role is Student
        if (session('current_role') == 'Student') {
            $student = $user->student;
            $data['programmeName'] = optional($student->programme)->name;
        }
        // Add lecturer name if role is Lecturer
        if (session('current_role') == 'Lecturer' && $user->lecturer) {
            $data['lecturerName'] = $user->first_name . ' ' . $user->last_name;
        }

        $pdf =  Pdf::loadView('exports.timetable', $data)->setPaper('A4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, "ict_weekend_timetable_" . $this->publishedVersion->published_at . ".pdf");
    }

    public function exportToExcel()
    {
        $user = Auth::user();
        $days = $this->days;
        $timeSlots = $this->timeSlots;
        $entries = collect($this->entries);
        $published_at = $this->publishedVersion->published_at;
        $programmeName = null;
        $lecturerName = null;
        if (session('current_role') == 'Student') {
            $student = $user->student;
            $programmeName = optional($student->programme)->name;
        }
        if (session('current_role') == 'Lecturer' && $user->lecturer) {
            $lecturerName = $user->first_name . ' ' . $user->last_name;
        }

        $filename = 'ict_weekend_timetable_' . $published_at . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        $callback = function () use ($days, $timeSlots, $entries, $programmeName, $lecturerName, $published_at) {
            $file = fopen('php://output', 'w');
            // Title
            fputcsv($file, ['Weekly Timetable']);
            if ($programmeName) {
                fputcsv($file, ["Programme: $programmeName"]);
            }
            if ($lecturerName) {
                fputcsv($file, ["Lecturer: $lecturerName"]);
            }
            fputcsv($file, []); // Empty row
            // Header row
            $header = ['Day / Time'];
            foreach ($timeSlots as $slot) {
                $header[] =
                    \Carbon\Carbon::parse($slot['start'])->format('H:i') . ' - ' .
                    \Carbon\Carbon::parse($slot['end'])->format('H:i');
            }
            fputcsv($file, $header);
            // Data rows
            foreach ($days as $day) {
                $row = [$day];
                foreach ($timeSlots as $slot) {
                    $cellEntries = $entries->filter(function ($entry) use ($day, $slot) {
                        return $entry['day'] === $day && $entry['start_time'] === $slot['start'];
                    });
                    if ($cellEntries->isNotEmpty()) {
                        $cellText = [];
                        foreach ($cellEntries as $entry) {
                            $cellText[] =
                                $entry['course_code'] . ' - ' . $entry['course_name'] . "\n" .
                                'Lecturer: ' . $entry['lecturer'] . "\n" .
                                'Venue: ' . $entry['venue'];
                        }
                        $row[] = implode("\n---\n", $cellText);
                    } else {
                        $row[] = '';
                    }
                }
                fputcsv($file, $row);
            }
            // Footer
            fputcsv($file, []);
            fputcsv($file, ["Published at $published_at"]);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        return view('livewire.timetable.personal-timetable');
    }
}
