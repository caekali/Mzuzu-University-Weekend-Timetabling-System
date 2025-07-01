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

    public function mount()
    {
        $this->stats = [
            [
                'title' => 'Total Users',
                'value' => User::count(),
                'icon' => 'users',
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
    }

    public function render()
    {
        return view('livewire.dashboard.admin-panel');
    }
}
