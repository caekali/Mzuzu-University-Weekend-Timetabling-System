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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MzuniSeeder extends Seeder
{
    public function run(): void
    {
        // department
        $ictDept = Department::create(['code' => 'ICT', 'name' => 'Information And Communication Technology']);
        $mathDept = Department::create(['code' => 'MATH', 'name' => 'Mathematics']);
        $commDept = Department::create(['code' => 'COMM', 'name' => 'Communication']);

        $commDeptCourses = [
            ['code' => 'COMM1101', 'name' => 'Communication Studies I', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 1, 'semester' => 1],
        ];

        foreach ($commDeptCourses as $commDeptCourse) {
            $commDept->courses()->create($commDeptCourse);
        }

        $mathDeptCourses = [
            ['code' => 'STAT2301', 'name' => 'Introduction to Statistical Analysis', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 2, 'semester' => 3],
            ['code' => 'STAT4705', 'name' => 'Big Data Analytics', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 4, 'semester' => 7],
            ['code' => 'MATH3505', 'name' => 'Optimization', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 3, 'semester' => 5],
            ['code' => 'STAT3505', 'name' => 'Stocastic Modelling ', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 3, 'semester' => 5],
            ['code' => 'MATH2306', 'name' => 'Linear Algebra', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 2, 'semester' => 3],
            ['code' => 'MATH1101', 'name' => 'Pre-calculus', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 1, 'semester' => 1],
        ];

        foreach ($mathDeptCourses as $mathDeptCourse) {
            $mathDept->courses()->create($mathDeptCourse);
        }

        $ictDeptCourses = [
            ['code' => 'BICT1101', 'name' => 'End User Computing', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 1, 'semester' => 1],
            ['code' => 'BICT1102', 'name' => 'Introduction To Programming With C', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 1, 'semester' => 1],
            ['code' => 'BICT3504', 'name' => 'Algorthms and Data Structures With Java', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 3, 'semester' => 5],
            ['code' => 'BICT2302', 'name' => 'Programming In Java', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 2, 'semester' => 3],
            ['code' => 'BICT2306', 'name' => 'Data Wrangling and Exploratory Data Analysis', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 2, 'semester' => 3],
            ['code' => 'BICT2304', 'name' => 'Computer Networks I', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 2, 'semester' => 3],
            ['code' => 'BICT2307', 'name' => 'Introduction to Cloud Computing', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 2, 'semester' => 3],
            ['code' => 'BICT3505', 'name' => 'Web Programming', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 3, 'semester' => 5],
            ['code' => 'BICT3502', 'name' => 'Research Methods', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 3, 'semester' => 5],
            ['code' => 'BICT4702', 'name' => 'Modelling and Simulation', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 4, 'semester' => 7],
            ['code' => 'BICT4703', 'name' => 'Network Administration and Information Security', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 4, 'semester' => 7],
            ['code' => 'BICT4704', 'name' => 'Enterpreneurship', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 4, 'semester' => 7],
            ['code' => 'BICT2401', 'name' => 'Operating System', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 2, 'semester' => 4],
            ['code' => 'BICT2307', 'name' => 'Web Design', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 2, 'semester' => 3],
            ['code' => 'BICT2403', 'name' => 'Computer networks II', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 2, 'semester' => 4],
            ['code' => 'BICT3504', 'name' => 'Mobile Telecommunication', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 3, 'semester' => 5],
            ['code' => 'BICT4701', 'name' => 'Software Engineering', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 4, 'semester' => 7],
            ['code' => 'BICT1103', 'name' => 'Computer and Communication Technology', 'num_of_students' => 20, 'lecture_hours' => 3, 'level' => 1, 'semester' => 1],
        ];

        foreach ($ictDeptCourses as $ictDeptCourse) {
            $ictDept->courses()->create($ictDeptCourse);
        }



        Programme::create(['code' => 'BSDS', 'name' => 'BSc Data Science', 'department_id' => $ictDept->id]);
        Programme::create(['code' => 'DICT', 'name' => 'Diploma in ICT', 'department_id' => $ictDept->id]);
        Programme::create(['code' => 'DUICT', 'name' => 'Diploma Upgrading in ICT', 'department_id' => $ictDept->id]);


        $lecturers = [
            ['first_name' => 'ezekiel', 'last_name' => 'namacha'],
            ['first_name' => 'lome', 'last_name' => 'longwe'],
            ['first_name' => 'chimango', 'last_name' => 'nyasulu'],
            ['first_name' => 'seyani', 'last_name' => 'nayeja'],
            ['first_name' => 'precious', 'last_name' => 'msonda'],
            ['first_name' => 'blessings', 'last_name' => 'ngwira'],
            ['first_name' => 'Enock', 'last_name' => "tung'ande"],
            ['first_name' => 'vision', 'last_name' => 'thondoya'],
            ['first_name' => 'josephy', 'last_name' => 'kumwenda'],
            ['first_name' => 'mr.', 'last_name' => 'nalivata'],
            ['first_name' => 'stanley', 'last_name' => 'ndebvu'],
            ['first_name' => 'mwekela', 'last_name' => ''],
            ['first_name' => 'prince', 'last_name' => 'goba'],
            ['first_name' => 'donald', 'last_name' => 'phiri'],
        ];

        foreach ($lecturers as $lecturer) {
            $firstName = Str::title($lecturer['first_name']);
            $lastName = Str::title($lecturer['last_name']);
            $email = strtolower($firstName . '.' . $lastName) . '@my.mzuni.ac.mw';
            $email = str_replace(["'", ' ', '..', '.@'], ['', '', '.', '@'], $email);

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'email_verified_at' => now(),
                    'password' => null,
                    'remember_token' => Str::random(10),
                ]
            );

            $user->assignRole('Lecturer');
            $user->lecturer()->create(['department_id' => $ictDept->id]);
        }


        $venues = [
            ['name' => 'English Lecture Room', 'capacity' => 60, 'is_lab' => 0],
            ['name' => 'Main Lecture Room', 'capacity' => 50, 'is_lab' => 0],
            ['name' => 'ICT LAB 1', 'capacity' => 70, 'is_lab' => 0],
            ['name' => 'ICT LAB 2', 'capacity' => 60, 'is_lab' => 0],
            ['name' => 'Geography Lecture Room', 'capacity' => 80, 'is_lab' => 0,],
            ['name' => 'ODEL ROOM A', 'capacity' => 70, 'is_lab' => 0],
            ['name' => 'ODEL ROOM B', 'capacity' => 70, 'is_lab' => 0],
        ];

        foreach ($venues as $venue) {
            Venue::create($venue);
        }

        DB::table('settings')->insert([
            ['id' => 1, 'key' => 'slot_duration', 'value' => '60', 'created_at' => '2025-06-29 12:52:52', 'updated_at' => '2025-06-29 19:06:12'],
            ['id' => 2, 'key' => 'break_duration', 'value' => '30', 'created_at' => '2025-06-29 12:52:52', 'updated_at' => '2025-06-30 12:28:22'],
            ['id' => 3, 'key' => 'start_time', 'value' => '07:00', 'created_at' => '2025-06-29 12:52:52', 'updated_at' => '2025-06-29 18:54:44'],
            ['id' => 4, 'key' => 'end_time', 'value' => '18:00', 'created_at' => '2025-06-29 12:52:52', 'updated_at' => '2025-06-29 12:52:52'],
        ]);
    }
}
