<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseAllocation;
use App\Models\CourseProgramme;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\Programme;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Models\Venue;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            MzuniSeeder::class

        ]);
    }
}
