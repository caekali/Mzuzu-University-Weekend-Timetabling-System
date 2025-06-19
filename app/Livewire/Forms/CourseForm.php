<?php

namespace App\Livewire\Forms;

use App\Models\Course;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CourseForm extends Form
{
    public $courseId = null;

    #[Validate('required|string|max:10')]
    public $code = '';

    #[Validate('required|string')]
    public $name = '';


    #[Validate('required|integer|min:1')]
    public $level = null;

    #[Validate('required|integer|min:1')]
    public $semester = null;


    #[Validate('required|string')]
    public $lecture_hours = null;

    #[Validate('required|integer|min:0')]
    public $num_of_students = null;

    #[Validate('required|exists:departments,id')]
    public $department_id = '';

    public function store()
    {
        $validated = $this->validate();

        if (!$this->courseId) {
            Course::create($validated);
        } else {
            Course::findOrFail($this->courseId)
                ->update($validated);
        }

        $this->reset('form');
        $this->resetValidation();
        
    }
}
