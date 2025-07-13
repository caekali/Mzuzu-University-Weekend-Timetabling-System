<?php

namespace App\Exports;

use App\Models\ScheduleEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ScheduleExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): \Illuminate\Contracts\View\View
    {
        $entries = ScheduleEntry::with(['course', 'lecturer.user', 'venue'])
            ->whereHas('scheduleVersion', fn($q) => $q->where('is_published', true))
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('exports.timetable-excel', [
            'entries' => $entries,
        ]);
    }
}
