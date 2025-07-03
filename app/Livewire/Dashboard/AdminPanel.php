<?php

namespace App\Livewire\Dashboard;

use App\Models\Course;
use App\Models\Department;
use App\Models\Programme;
use App\Models\Student;
use App\Models\User;
use Livewire\Component;

class AdminPanel extends Component
{
    public  $stats = [];
    public $cards = [];

    public function mount()
    {
        $this->stats = [
            [
                'to' => 'departments',
                'title' => 'Departments',
                'icon' => 'building',
                'description' => 'Manage academic departments and organizational structure',
                'color' => 'blue'
            ],
            [
                'title' => 'Total Courses',
                'value' => Course::count(),
                'icon' => 'book-open',
            ],
            [
                'title' => 'Departments',
                'value' => Department::count(),
                'icon' => 'building',
            ],
            [
                'title' => 'Programmes',
                'value' => Programme::count(),
                'icon' => 'school',
            ],
        ];

        $this->cards = [
                [
                    'to' => '/departments',
                    'icon' => 'building',
                    'title' => 'Departments',
                    'description' => 'Manage academic departments and organizational structure',
                    'color' => 'blue',
                ],
                [
                    'to' => '/venues',
                    'icon' => 'map-pin',
                    'title' => 'Venues',
                    'description' => 'Manage classrooms, labs, and teaching facilities',
                    'color' => 'green',
                ],
                [
                    'to' => '/settings',
                    'icon' => 'settings',
                    'title' => 'Venue Constraints',
                    'description' => 'Set availability and restrictions for venues',
                    'color' => 'purple',
                ],
                [
                    'to' => '/timetable',
                    'icon' => 'calendar',
                    'title' => 'Full Timetable',
                    'description' => 'View and manage the complete university schedule',
                    'color' => 'orange',
                ],
                [
                    'to' => '/timetable-generation',
                    'icon' => 'cpu',
                    'title' => 'Generate Timetable',
                    'description' => 'Create optimized schedules using Genetics algorithm',
                    'color' => 'indigo',
                ],
                [
                    'to' => '/users',
                    'icon' => 'users',
                    'title' => 'Users',
                    'description' => 'Manage user accounts and permissions',
                    'color' => 'teal',
                ],
            ];
    }

    public function render()
    {
        return view('livewire.dashboard.admin-panel');
    }
}
