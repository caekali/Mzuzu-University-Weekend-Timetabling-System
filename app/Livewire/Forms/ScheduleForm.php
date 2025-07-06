<?php

namespace App\Livewire\Forms;

use App\DTO\ScheduleEntryDTO;
use App\Models\LecturerCourseAllocation;
use App\Models\ScheduleEntry;
use Livewire\Attributes\Validate;
use Livewire\Form;


class ScheduleForm extends Form
{
    public $scheduleEntryId;

    public $versionId;

    #[Validate('required')]
    public $allocationId;

    #[Validate('required|exists:venues,id')]
    public $venue_id;

    #[Validate('required')]
    public $start_time;

    #[Validate('required')]
    public $end_time;

    #[Validate('required|string')]
    public $day;

    public $entries = [];

    public function store()
    {
        $this->validate();

        $allocation = LecturerCourseAllocation::with(['programmes', 'course'])->findOrFail($this->allocationId);

        $sessionsPerWeek = $allocation->course->lecture_hours;
        $baseTime = \Carbon\Carbon::createFromFormat('H:i', $this->start_time);

        foreach ($allocation->programmes as $programme) {
            if ($this->scheduleEntryId) {
                $entries = ScheduleEntry::where('lecturer_id', $allocation->lecturer_id)
                    ->where('level', $allocation->level)
                    ->where('course_id', $allocation->course_id)
                    ->where('programme_id', $programme->id)
                    ->where('schedule_version_id', $this->versionId)
                    ->get();


                $start = $baseTime->copy();

                foreach ($entries as $entry) {
                    $entry->update([
                        'venue_id' => $this->venue_id,
                        'day' => $this->day,
                        'start_time' => $start->format('H:i:s'),
                        'end_time' => $start->copy()->addHour()->format('H:i:s'),
                    ]);

                    $this->entries[] = $entry;
                    $start->addHour();
                }
            } else {
                for ($i = 0; $i < $sessionsPerWeek; $i++) {
                    $start = $baseTime->copy()->addHours($i);
                    $end = $start->copy()->addHour();
                    $this->entries[] = ScheduleEntry::create([
                        'course_id' => $allocation->course_id,
                        'lecturer_id' => $allocation->lecturer_id,
                        'level' => $allocation->level,
                        'programme_id' => $programme->id,
                        'venue_id' => $this->venue_id,
                        'day' => $this->day,
                        'start_time' => $start->format('H:i:s'),
                        'end_time' => $end->format('H:i:s'),
                        'schedule_version_id' => $this->versionId,
                    ]);
                }
            }
        }

        // $this->reset();
    }

    public function deleteEntries()
    {
        // Find the first session
        $firstSession = ScheduleEntry::findOrFail($this->scheduleEntryId);

        // Delete all matching entries (including the first one)
        $entries = ScheduleEntry::where('lecturer_id', $firstSession->lecturer_id)
            ->where('level', $firstSession->level)
            ->where('course_id', $firstSession->course_id)
            ->where('schedule_version_id', $firstSession->schedule_version_id)
            ->get();

        foreach ($entries as $entry) {
            $entry->forceDelete();
        }
    }
}
