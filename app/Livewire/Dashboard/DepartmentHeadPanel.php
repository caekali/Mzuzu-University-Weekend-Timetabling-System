<?php

namespace App\Livewire\Dashboard;

use App\Models\Course;
use App\Models\Department;
use App\Models\Programme;
use App\Models\Student;
use Livewire\Component;

class DepartmentHeadPanel extends Component
{
    public  $stats = [];

    public function mount()
    {
        $this->stats = [
            [
                'title' => 'Total Students',
                'value' => Student::count(),
                'icon' => 'users',
            ],
            [
                'title' => 'Total Courses',
                'value' => Course::count(),
                'icon' => 'book-open',
            ],
            [
                'title' => 'Programmes',
                'value' => Programme::count(),
                'icon' => 'school',
            ],
        ];
    }


    public function render()
    {
        return view('livewire.dashboard.department-head-panel');
    }
}
