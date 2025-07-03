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
                'to' => route('programmes'),
                'icon' => 'school',
                'title' => 'Programmes',
                'description' => 'Manage academic programmes',
                'color' => 'blue',
            ],
            [
                'to' => route('courses'),
                'icon' => 'book-open',
                'title' => 'Courses',
                'description' => 'Manage course offerings and curriculum',
                'color' => 'green',
            ],
            [
                'to' => route('course-allocations'),
                'icon' => 'briefcase',
                'title' => 'Course Allocations',
                'description' => 'Assign lecturers to courses',
                'color' => 'purple',
            ],
            [
                'to' => route('lecturer.constraints'),
                'icon' => 'clock',
                'title' => 'Lecturer Constraints',
                'description' => 'Set availability and teaching preferences',
                'color' => 'orange',
            ],
            [
                'to' => route('full.timetable'),
                'icon' => 'calendar',
                'title' => 'Full Timetable',
                'description' => 'View full schedule',
                'color' => 'indigo',
            ],
        ];
    }


    public function render()
    {
        return view('livewire.dashboard.department-head-panel');
    }
}
