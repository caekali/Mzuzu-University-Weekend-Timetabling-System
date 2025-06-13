<?php

namespace App\Livewire\Timetable;

use App\Livewire\Forms\ScheduleForm;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Programme;
use App\Models\ScheduleEntry;
use App\Models\Venue;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ScheduleModal extends Component
{
    use WireUiActions;
    protected $listeners = ['openModal'];
    public ScheduleForm $form;

    public function openModal($id = null, $day, $startTime, $endTime)
    {

        $this->form->reset();
        $this->resetErrorBag();

        $this->form->day = $day;
        $this->form->start_time = $startTime;
        $this->form->end_time = $endTime;
        
        if ($id) {
            $scheduleEntry = ScheduleEntry::findOrFail($id);
            $this->form->scheduleEntryId = $scheduleEntry->id;
            $this->form->course_id = $scheduleEntry->course_id;
            $this->form->lecturer_id = $scheduleEntry->lecturer_id;
            $this->form->venue_id = $scheduleEntry->venue_id;
            $this->form->programme_id = $scheduleEntry->programme_id;
        }

        $this->modal()->open('schedule-modal');
    }

    public function save()
    {
        $this->form->store();
        $this->notification()->success(
            'Saved',
            'Schedule saved successfully.'
        );
        $this->modal()->close('schedule-modal');
        $this->dispatch('refresh-list');
    }
    public function render()
    {
        $courses = Course::all();

        $courses->transform(function ($course) {
            $data = [
                'id'    => $course->id,
                'name'  => $course->code . ' - ' . $course->name,
            ];
            return $data;
        });
        $programmes = Programme::all();

        $venues = Venue::all();
        $lecturers = Lecturer::all();

        $lecturers->transform(function ($lecturer) {
            $data = [
                'id'    => $lecturer->id,
                'name'  => $lecturer->user->first_name . ' ' . $lecturer->user->last_name,
            ];
            return $data;
        });

        $venues->transform(function ($venue) {
            $data = [
                'id'    => $venue->id,
                'name'  => $venue->name . ' (Capacity : ' . $venue->capacity . ')'
            ];
            return $data;
        });

        return view('livewire.timetable.schedule-modal', compact('courses', 'programmes', 'venues', 'lecturers'));
    }
}
