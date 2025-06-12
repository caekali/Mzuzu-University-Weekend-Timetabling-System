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
            UserSeeder::class
        ]);

        Department::factory(3)->create()->each(function ($department) {
            Programme::factory(2)->create(['department_id' => $department->id])->each(function ($programme) use ($department) {
                Course::factory(5)->create([
                    'department_id' => $department->id,
                    'level' => 100,
                    'semester' => 1
                ])->each(function ($course) use ($programme) {
                    CourseProgramme::create([
                        'course_id' => $course->id,
                        'programme_id' => $programme->id
                    ]);
                });
            });
        });

        User::factory(10)->create()->each(function ($user) {
            $user->update(['is_active' => true]);
            Lecturer::create([
                'user_id' => $user->id,
                'department_id' => Department::inRandomOrder()->first()->id
            ]);
        });

        // Allocate courses to lecturers
        $courseProgrammes = CourseProgramme::all();
        foreach ($courseProgrammes as $cp) {
            CourseAllocation::create([
                'course_programme_id' => $cp->id,
                'lecturer_id' => Lecturer::inRandomOrder()->first()->id
            ]);
        }

        Venue::factory(5)->create();

        // Create students
        Programme::all()->each(function ($programme) {
            User::factory(10)->create()->each(function ($user) use ($programme) {
                $user->update(['is_active' => true]);
                Student::create([
                    'user_id' => $user->id,
                    'programme_id' => $programme->id,
                    'level' => 100
                ]);
            });
        });
    
    }
}
