<?php

namespace App\Livewire\Timetable;

use App\Livewire\Forms\ScheduleForm;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\LecturerCourseAllocation;
use App\Models\Programme;
use App\Models\ScheduleDay;
use App\Models\ScheduleEntry;
use App\Models\Venue;
use App\Services\GeneticAlgorithm\ConstraintViolationChecker;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ScheduleModal extends Component
{
    use WireUiActions;
    protected $listeners = ['openModal'];

    public ScheduleForm $form;

    public $programmes;

    public $venues;


    public $lecturerAllocations = [];



    public $scheduleEntryId;

    public $days = [];

    public function mount()
    {
        $this->days = ScheduleDay::where('enabled', true)->pluck('name')->sort()->toArray();
        $this->venues = Venue::all()
            ->transform(fn($venue) => [
                'id' => $venue->id,
                'name' => $venue->name . ' (Capacity: ' . $venue->capacity . ')',
            ])
            ->toArray();


        $this->lecturerAllocations = LecturerCourseAllocation::with(['course', 'lecturer', 'programmes'])->get()
            ->transform(fn($allocation) => [
                'id' => $allocation->id,
                'label' => $allocation->lecturer->user->first_name . ' ' . $allocation->lecturer->user->last_name . ' (' . $allocation->course->code . ' : ' . $allocation->course->name . ', Level : ' . $allocation->level . ')',

            ])->toArray();
    }

    public function openModal($version, $id = null, $day = null, $startTime = null, $endTime = null)
    {

        $this->form->reset();
        $this->resetErrorBag();

        $this->form->day = $day;
        $this->form->start_time = $startTime;
        $this->form->end_time = $endTime;
        $this->form->versionId = $version;


        if ($id) {
            $scheduleEntry = ScheduleEntry::findOrFail($id);
            $allocation = LecturerCourseAllocation::with(['programmes', 'course'])->where('lecturer_id', $scheduleEntry->lecturer_id)
                ->where('level', $scheduleEntry->level)
                ->where('course_id', $scheduleEntry->course_id)
                ->first();

            $this->form->allocationId = $allocation->id;
            $this->scheduleEntryId = $id;

            $this->form->scheduleEntryId = $scheduleEntry->id;
            $this->form->venue_id = $scheduleEntry->venue_id;
        }

        $this->modal()->open('schedule-modal');
    }




    public function save()
    {
        $this->form->store();
        $checker = new ConstraintViolationChecker();
        $violations = $checker->check($this->form->entries);


        if (!empty($violations)) {
            foreach ($violations as $violation) {
                $this->notification()->send([
                    'icon' => 'warning',
                    'title' => 'Constraint Violation',
                    'description' => $violation['message'],
                    'timeout' => false
                ]);
            }
        } else {
            $this->notification()->success(
                title: 'Saved',
                description: 'Schedule saved successfully.'
            );
        }

        $this->modal()->close('schedule-modal');
        $this->dispatch('refresh-list');
    }

    public function deleteEntries()
    {
        $this->form->deleteEntries();
        $this->notification()->success(
            'Deletion',
            'Schedule Entery and other sessions deleted successfully.'
        );

        $this->modal()->close('schedule-modal');
        $this->dispatch('refresh-list');
    }

    public function render()
    {
        return view('livewire.timetable.schedule-modal');
    }
}
