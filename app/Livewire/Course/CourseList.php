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
    use WireUiActions,WithPagination ,WithoutUrlPagination;
   
    public $search = '';

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
        $courses = Course::with('department')->latest()->paginate(6);

        return view('livewire.course.course-list', compact('courses'));
    }
}
