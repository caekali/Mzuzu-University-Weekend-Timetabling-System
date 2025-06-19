<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Programme;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Venue;
use App\Models\CourseProgramme;
use App\Models\CourseAllocation;
use App\Models\Role;
use App\Models\User;

class MzuniSeeder extends Seeder
{
    public function run(): void
    {
        // === Departments ===
        $csDept = Department::create(['code' => 'CSD', 'name' => 'Computer Science']);
        $mathDept = Department::create(['code' => 'MTH', 'name' => 'Mathematics']);
        $eduDept = Department::create(['code' => 'EDU', 'name' => 'Education']);

        // === Programmes ===
        $csProg = Programme::create(['code' => 'BSC-CS', 'name' => 'BSc Computer Science', 'department_id' => $csDept->id]);
        $csWProg = Programme::create(['code' => 'BSC-CS-W', 'name' => 'BSc Computer Science (Weekend)', 'department_id' => $csDept->id]);

        $mathProg = Programme::create(['code' => 'BSC-MATH', 'name' => 'BSc Mathematics', 'department_id' => $mathDept->id]);
        $statsProg = Programme::create(['code' => 'BSC-STAT', 'name' => 'BSc Statistics', 'department_id' => $mathDept->id]);

        $eduSciProg = Programme::create(['code' => 'BED-SCI', 'name' => 'BEd Science', 'department_id' => $eduDept->id]);
        $eduWProg = Programme::create(['code' => 'BED-SCI-W', 'name' => 'BEd Science (Weekend)', 'department_id' => $eduDept->id]);

        // === Courses ===
        $courses = [
            ['code' => 'CS101', 'name' => 'Intro to Programming', 'students' => 120, 'hours' => 3, 'level' => 1, 'sem' => 1, 'dept' => $csDept->id],
            ['code' => 'CS201', 'name' => 'Data Structures', 'students' => 100, 'hours' => 3, 'level' => 2, 'sem' => 1, 'dept' => $csDept->id],
            ['code' => 'CS301', 'name' => 'Operating Systems', 'students' => 90, 'hours' => 3, 'level' => 3, 'sem' => 2, 'dept' => $csDept->id],
            ['code' => 'CSW101', 'name' => 'Weekend Programming', 'students' => 60, 'hours' => 2, 'level' => 1, 'sem' => 1, 'dept' => $csDept->id],

            ['code' => 'MA101', 'name' => 'Calculus I', 'students' => 110, 'hours' => 3, 'level' => 1, 'sem' => 1, 'dept' => $mathDept->id],
            ['code' => 'MA201', 'name' => 'Linear Algebra', 'students' => 100, 'hours' => 3, 'level' => 2, 'sem' => 2, 'dept' => $mathDept->id],
            ['code' => 'ST101', 'name' => 'Intro to Statistics', 'students' => 90, 'hours' => 3, 'level' => 1, 'sem' => 1, 'dept' => $mathDept->id],
            ['code' => 'ST301', 'name' => 'Advanced Inference', 'students' => 80, 'hours' => 2, 'level' => 3, 'sem' => 2, 'dept' => $mathDept->id],

            ['code' => 'ED101', 'name' => 'Education Foundations', 'students' => 100, 'hours' => 3, 'level' => 1, 'sem' => 1, 'dept' => $eduDept->id],
            ['code' => 'ED201', 'name' => 'Child Psychology', 'students' => 80, 'hours' => 2, 'level' => 2, 'sem' => 1, 'dept' => $eduDept->id],
            ['code' => 'EDW101', 'name' => 'Weekend Foundations', 'students' => 60, 'hours' => 2, 'level' => 1, 'sem' => 1, 'dept' => $eduDept->id],
            ['code' => 'ED301', 'name' => 'Curriculum Development', 'students' => 70, 'hours' => 3, 'level' => 3, 'sem' => 2, 'dept' => $eduDept->id],
        ];

        $courseModels = [];
        foreach ($courses as $data) {
            $courseModels[$data['code']] = Course::create([
                'code' => $data['code'],
                'name' => $data['name'],
                'num_of_students' => $data['students'],
                'weekly_hours' => $data['hours'],
                'level' => $data['level'],
                'semester' => $data['sem'],
                'department_id' => $data['dept'],
            ]);
        }

        // // === Link Courses to Programmes ===
        // $links = [
        //     'CS101' => [$csProg, $csWProg],
        //     'CS201' => [$csProg],
        //     'CS301' => [$csProg],
        //     'CSW101' => [$csWProg],
        //     'MA101' => [$mathProg, $statsProg],
        //     'MA201' => [$mathProg],
        //     'ST101' => [$statsProg],
        //     'ST301' => [$statsProg],
        //     'ED101' => [$eduSciProg],
        //     'ED201' => [$eduSciProg],
        //     'EDW101' => [$eduWProg],
        //     'ED301' => [$eduSciProg, $eduWProg],
        // ];

        // foreach ($links as $code => $programmes) {
        //     foreach ($programmes as $prog) {
        //         CourseProgramme::create([
        //             'course_id' => $courseModels[$code]->id,
        //             'programme_id' => $prog->id,
        //         ]);
        //     }
        // }

        // === Users and Lecturers ===
        $lecturers = [];
        $names = [['Alice','Banda'], ['Ben','Chirwa'], ['Chisomo','Mwale'], ['Dorothy','Kamanga']];
        foreach ($names as $i => $name) {
            $user = User::create([
                'first_name' => $name[0],
                'last_name' => $name[1],
                'email' => strtolower($name[0].$name[1]) . '@my.mzuni.ac.mw',
                'password' => bcrypt('password'),
            ]);
                    $user->roles()->sync(Role::where('name','Lecturer')->first()->id);
            $user->lecturer()->create(['department_id' => rand(1,3)]);
        }

        // // === Allocate Lecturers ===
        // $i = 0;
        // foreach (CourseProgramme::all() as $cp) {
        //     CourseAllocation::create([
        //         'course_programme_id' => $cp->id,
        //         'lecturer_id' => rand(),
        //     ]);
        // }

        // === Venues ===
        Venue::insert([
            ['name' => 'Lecture Hall A', 'capacity' => 120, 'is_lab' => false],
            ['name' => 'Lecture Hall B', 'capacity' => 80, 'is_lab' => false],
            ['name' => 'Computer Lab 1', 'capacity' => 40, 'is_lab' => true],
            ['name' => 'Math Tutorial Room', 'capacity' => 60, 'is_lab' => false],
        ]);
    }
}
