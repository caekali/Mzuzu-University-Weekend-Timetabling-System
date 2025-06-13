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

    public $courses;
    public $programmes;
    public $venues;
    public $lecturers;

    public function mount()
    {
        $this->programmes = Programme::all();
        $this->courses = Course::all()
            ->transform(fn($course) => [
                'id' => $course->id,
                'name' => $course->code . ' - ' . $course->name,
            ])
            ->toArray();

        $this->venues = Venue::all()
            ->transform(fn($venue) => [
                'id' => $venue->id,
                'name' => $venue->name . ' (Capacity: ' . $venue->capacity . ')',
            ])
            ->toArray();

        $this->lecturers = Lecturer::with('user')->get()
            ->transform(fn($lecturer) => [
                'id' => $lecturer->id,
                'name' => $lecturer->user->first_name . ' ' . $lecturer->user->last_name,
            ])
            ->toArray();
    }

    public function openModal($id = null, $day = null, $startTime = null, $endTime = null)
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
        return view('livewire.timetable.schedule-modal');
    }
}
