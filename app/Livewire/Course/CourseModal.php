<?php

namespace App\Livewire\Course;

use App\Livewire\Forms\CourseForm;
use App\Models\Course;
use App\Models\Department;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class CourseModal extends Component
{
    use WireUiActions;
    protected $listeners = ['openModal'];

    public CourseForm $form;

    public function openModal($id = null)
    {
        $this->form->reset();
        $this->form->resetErrorBag();

        if ($id) {
            $course = Course::findOrFail($id);
            $this->form->courseId = $course->id;
            $this->form->code = $course->code;
            $this->form->name = $course->name;
            $this->form->level = $course->level;
            $this->form->semester = $course->semester;
            $this->form->weekly_hours = $course->weekly_hours;
            $this->form->num_of_students = $course->num_of_students;
            $this->form->department_id = $course->department_id;
        }

        $this->modal()->open('course-modal');
    }

    public function save()
    {
        $this->form->store();
        $this->notification()->success(
            'Saved',
            'Course saved successfully.'
        );
        $this->modal()->close('course-modal');
        $this->dispatch('refresh-list');
    }
    public function render()
    {
        return view('livewire.course.course-modal', [
            'departments' => Department::all(),
        ]);
    }
}
