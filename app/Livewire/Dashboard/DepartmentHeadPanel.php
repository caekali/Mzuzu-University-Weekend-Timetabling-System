<?php

namespace App\Livewire\Dashboard;

use App\Models\Course;
use App\Models\Department;
use App\Models\Programme;
use App\Models\Student;
use Livewire\Component;

class DepartmentHeadPanel extends Component
{
    public  $cards = [];

    public function mount()
    {
        $this->cards = [
            [
                'to' => '/programmes',
                'icon' => 'school',
                'title' => 'Programmes',
                'description' => 'Manage academic programmes and degree structures',
                'color' => 'blue',
            ],
            [
                'to' => '/courses',
                'icon' => 'book-open',
                'title' => 'Courses',
                'description' => 'Manage course offerings and curriculum',
                'color' => 'green',
            ],
            [
                'to' => '/course-allocations',
                'icon' => 'briefcase',
                'title' => 'Course Allocations',
                'description' => 'Assign lecturers to courses and manage workload',
                'color' => 'purple',
            ],
            [
                'to' => '/lecturer-constraints',
                'icon' => 'clock',
                'title' => 'Lecturer Constraints',
                'description' => 'Set availability and teaching preferences',
                'color' => 'orange',
            ],
            [
                'to' => '/full-timetable',
                'icon' => 'calendar',
                'title' => 'Full Timetable',
                'description' => 'View department schedule and monitor classes',
                'color' => 'indigo',
            ],
        ];
    }


    public function render()
    {
        return view('livewire.dashboard.department-head-panel');
    }
}
