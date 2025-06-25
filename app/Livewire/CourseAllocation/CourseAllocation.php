<?php

namespace App\Livewire\CourseAllocation;

use App\Models\Lecturer;
use App\Models\LecturerCourseAllocation;
use App\Models\Programme;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use WireUi\Attributes\Mount;
use WireUi\Traits\WireUiActions;

class CourseAllocation extends Component
{
    use WireUiActions, WithPagination, WithoutUrlPagination;


    public  $headers = [
        'id' => 'ID',
        'lecturer' => 'Lecturer',
        'course' => 'Course',
        'level' => 'Level',
        'programmes' => 'Programmes'
    ];

    public $programmes = [];
    public $lecturers = [];

    public $selectedLevel;

    public $selectedLecturer;

    public $selectedProgramme;


    public function mount()
    {
        $this->programmes = Programme::all()->map(function ($programme) {
            return [
                'id' => $programme->id,
                'name' => $programme->name,
            ];
        });

        $this->lecturers = Lecturer::with('user')->get()->map(function ($lecturer) {
            return [
                'id' => $lecturer->id,
                'name' => $lecturer->user->first_name . ' ' . $lecturer->user->last_name,
            ];
        });
    }

    public function openModal($id = null)
    {
        $this->dispatch('openModal', $id)->to('course-allocation.course-allocation-modal');
        $this->dispatch('refresh-list');
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
        $allocations = LecturerCourseAllocation::with(['lecturer.user', 'course', 'programmes'])
            ->when($this->selectedLecturer, fn($q) =>
            $q->where('lecturer_id', $this->selectedLecturer))
            ->when(
                $this->selectedLevel,
                fn($q) =>
                $q->where('level', $this->selectedLevel)
            )->when(
                $this->selectedProgramme,
                fn($q) =>
                $q->whereHas('programmes', fn($q) => $q->where('programmes.id', $this->selectedProgramme))

            )->paginate(8);


        $allocations->setCollection($allocations->getCollection()->transform(function ($allocation) {
            return [
                'id' => $allocation->id,
                'lecturer' => "{$allocation->lecturer->user->first_name} {$allocation->lecturer->user->last_name}",
                'course' => "{$allocation->course->code} - {$allocation->course->name}",
                'programmes' => $allocation->programmes->pluck('code')->join(', '),
                'level' => $allocation->level
            ];
        }));


        return view('livewire.course-allocation.course-allocation', compact('allocations'));
    }
}
