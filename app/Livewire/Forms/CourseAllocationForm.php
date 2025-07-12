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
    public $level;

    public function store(): bool
    {
        $validated = $this->validate();

        // Check for duplicates: same lecturer, course, level and any overlapping programme
        $existingAllocations = LecturerCourseAllocation::where('lecturer_id', $validated['lecturer_id'])
            ->where('course_id', $validated['course_id'])
            ->where('level', $validated['level'])
            ->when($this->allocationId, fn($q) => $q->where('id', '!=', $this->allocationId))
            ->with('programmes')
            ->get();

        foreach ($existingAllocations as $allocation) {
            $existingProgrammeIds = $allocation->programmes->pluck('id')->toArray();

            if (count(array_intersect($validated['programme_ids'], $existingProgrammeIds)) > 0) {
                $this->addError('programme_ids', 'This allocation with one of programmes and level already exists.');
                return false;
            }
        }

        // Save or update allocation
        if ($this->allocationId) {
            $allocation = LecturerCourseAllocation::findOrFail($this->allocationId);
            $allocation->update([
                'lecturer_id' => $validated['lecturer_id'],
                'course_id' => $validated['course_id'],
                'level' => $validated['level'],
            ]);
            $allocation->programmes()->sync($validated['programme_ids']);
        } else {
            $allocation = LecturerCourseAllocation::create([
                'lecturer_id' => $validated['lecturer_id'],
                'course_id' => $validated['course_id'],
                'level' => $validated['level'],
            ]);
            $allocation->programmes()->sync($validated['programme_ids']);
        }

        $this->reset('form');
        $this->resetValidation();

        return true;
    }
}
