<?php

namespace App\Livewire\Course;

use App\Models\Course;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class CourseList extends Component
{
    use WireUiActions, WithPagination, WithoutUrlPagination;

    public $search = '';

    public $headers = [
        'code' => 'Code',
        'name' => 'Name',
        'lecture_hours' => 'Lecture Hours',
        'level' => 'Level',
        'semester' => 'Semester',
        'department' => 'Department',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->dispatch('openModal', $id)->to('course.course-modal');
    }

    public function confirmDelete($id)
    {
        $this->dialog()->confirm([
            'title'       => 'Delete Course?',
            'description' => 'Are you sure you want to delete this course?',
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
        Course::findOrFail($id)->delete();

        $this->notification()->success(
            'Deleted',
            'Course deleted successfully.'
        );

        $this->dispatch('refresh-list');
    }

    #[On('refresh-list')]
    public function refresh() {}


    public function render()
    {
        $courses = Course::with('department')
            ->when(trim($this->search), function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . trim($this->search) . '%')
                        ->orWhere('code', 'like', '%' . trim($this->search) . '%');
                });
            })
            ->latest()
            ->paginate(6);

        $courses->setCollection(
            $courses->getCollection()->transform(function ($course) {
                return [
                    'id' => $course->id,
                    'code' => $course->code,
                    'name' => $course->name,
                    'lecture_hours' => $course->lecture_hours,
                    'level' => $course->level,
                    'semester' => $course->semester,
                    'department' => $course->department->name ?? 'N/A',
                    'num_of_students' => $course->num_of_students,
                ];
            })
        );

        return view('livewire.course.course-list', compact('courses'));
    }
}
