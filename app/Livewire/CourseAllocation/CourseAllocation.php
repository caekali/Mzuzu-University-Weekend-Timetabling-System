<?php

namespace App\Livewire\CourseAllocation;

use App\Models\LecturerCourseAllocation;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class CourseAllocation extends Component
{
    use WireUiActions, WithPagination, WithoutUrlPagination;


    public  $headers = [
        'id' => 'ID',
        'lecturer' => 'Lecturer',
        'course' => 'Course',
        'programmes' => 'Programmes'
    ];

    public function openModal($id = null)
    {
        $this->dispatch('openModal', $id)->to('course-allocation.course-allocation-modal');
    }

    public function confirmDelete($id)
    {
        $this->dialog()->confirm([
            'title'       => 'Delete Allocation?',
            'description' => 'Are you sure you want to delete this Course Allocation?',
            'icon'        => 'warning',
            'accept'      => [
                'label'  => 'Yes, Delete',
                'method' => 'delete',
                'params' => $id,
            ],
            'reject' => [
                'label'  => 'Cancel',
            ],
        ]);
    }

    public function delete($id)
    {
        LecturerCourseAllocation::findOrFail($id)->delete();
        $this->notification()->success(
            'Deleted',
            'Allocation deleted successfully.'
        );

        $this->dispatch('refresh-list');
    }

    #[On('refresh-list')]
    public function refresh() {}

    public function render()
    {
        $allocations = LecturerCourseAllocation::with(['lecturer.user', 'course', 'programmes'])->paginate(8)->map(function ($allocation) {
            return [
                'id' => $allocation->id,
                'lecturer' => "{$allocation->lecturer->user->first_name} {$allocation->lecturer->user->last_name}",
                'course' => "{$allocation->course->code} - {$allocation->course->name}",
                'programmes' => $allocation->programmes->pluck('code')->join(', '),
            ];
        });

        return view('livewire.course-allocation.course-allocation',compact('allocations'));
    }
}
