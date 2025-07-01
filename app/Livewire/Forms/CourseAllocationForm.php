<?php

namespace App\Livewire\Forms;

use App\Models\LecturerCourseAllocation;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CourseAllocationForm extends Form
{
    public ?int $allocationId = null;

    #[Validate('required|exists:lecturers,id')]
    public ?int $lecturer_id = null;

    #[Validate('required|exists:courses,id')]
    public ?int $course_id = null;

    #[Validate('required|array|min:1')]
    public array $programme_ids = [];

    #[Validate('required')]
    public  $level;

    public function store(): void
    {
        $validated = $this->validate();

        if ($this->allocationId) {
            $allocation = LecturerCourseAllocation::findOrFail($this->allocationId);
            $allocation->update([
                'lecturer_id' => $validated['lecturer_id'],
                'course_id' => $validated['course_id'],
                'level' => $validated['level']
            ]);
            $allocation->programmes()->sync($validated['programme_ids']);
        } else {
            $allocation = LecturerCourseAllocation::create([
                'lecturer_id' => $validated['lecturer_id'],
                'course_id' => $validated['course_id'],
                'level' => $validated['level']
            ]);
            $allocation->programmes()->sync($validated['programme_ids']);
        }

        $this->reset();
    }
}
