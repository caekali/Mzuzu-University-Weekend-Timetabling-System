<?php

namespace App\Livewire\CourseAllocation;

use App\Livewire\Forms\CourseAllocationForm;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\LecturerCourseAllocation;
use App\Models\Programme;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class CourseAllocationModal extends Component
{
    use WireUiActions;

    protected $listeners = ['openModal'];

    public $programmes;
    public $courses;
    public $lecturers;

    public CourseAllocationForm $form;

    public function mount()
    {
        $this->programmes = Programme::all();
        $this->courses = Course::all()->map(function ($course) {
            return [
                'id'    => $course->id,
                'name' => "{$course->code} - {$course->name}",
            ];
        });

        $this->lecturers = Lecturer::with('user')->get()->map(function ($lecturer) {
            return [
                'id'   => $lecturer->id,
                'name' => $lecturer->user->first_name . ' ' . $lecturer->user->last_name,
            ];
        });
    }

    public function openModal($id = null)
    {
        $this->form->reset();
        $this->form->resetErrorBag();
        if ($id) {
            $lecturerCourseAllocation = LecturerCourseAllocation::findOrFail($id);
            $this->form->allocationId = $lecturerCourseAllocation->id;
            $this->form->lecturer_id = $lecturerCourseAllocation->lecturer_id;
            $this->form->course_id = $lecturerCourseAllocation->course_id;
            $this->form->programme_ids = $lecturerCourseAllocation->programmes->pluck('id')->toArray();
            $this->form->level = $lecturerCourseAllocation->level;
        }

        $this->modal()->open('course-allocation-modal');
    }



    public function save()
    {
        $this->form->store();
        $this->notification()->success(
            'Saved',
            'Allocation saved successfully.'
        );
        $this->modal()->close('course-allocation-modal');
        $this->dispatch('refresh-list');
    }
    public function render()
    {
        return view('livewire.course-allocation.course-allocation-modal');
    }
}
