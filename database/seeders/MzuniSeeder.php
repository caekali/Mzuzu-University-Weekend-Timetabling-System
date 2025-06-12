<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Programme;
use App\Models\Course;

class MzuniSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Computer Science Department' => [
                // Regular programmes
                'BSc Computer Science' => [
                    ['code' => 'CS101', 'name' => 'Introduction to Computer Science', 'credit_hours' => 3],
                    ['code' => 'CS201', 'name' => 'Data Structures and Algorithms', 'credit_hours' => 3],
                    ['code' => 'CS301', 'name' => 'Operating Systems', 'credit_hours' => 3],
                ],
                'BSc Information Systems' => [
                    ['code' => 'IS101', 'name' => 'Fundamentals of Information Systems', 'credit_hours' => 3],
                    ['code' => 'IS201', 'name' => 'Database Systems', 'credit_hours' => 3],
                    ['code' => 'IS301', 'name' => 'Systems Analysis and Design', 'credit_hours' => 3],
                ],
                // Weekend programmes
                'BSc Computer Science (Weekend)' => [
                    ['code' => 'CSW101', 'name' => 'Intro to CS (Weekend)', 'credit_hours' => 3],
                    ['code' => 'CSW201', 'name' => 'Algorithms (Weekend)', 'credit_hours' => 3],
                    ['code' => 'CSW301', 'name' => 'Operating Systems (Weekend)', 'credit_hours' => 3],
                ],
                'BSc Information Systems (Weekend)' => [
                    ['code' => 'ISW101', 'name' => 'Info Systems Basics (Weekend)', 'credit_hours' => 3],
                    ['code' => 'ISW201', 'name' => 'DB Systems (Weekend)', 'credit_hours' => 3],
                    ['code' => 'ISW301', 'name' => 'Systems Design (Weekend)', 'credit_hours' => 3],
                ],
            ],
            'Mathematics Department' => [
                'BSc Mathematics' => [
                    ['code' => 'MA101', 'name' => 'Calculus I', 'credit_hours' => 3],
                    ['code' => 'MA201', 'name' => 'Linear Algebra', 'credit_hours' => 3],
                ],
                'BSc Statistics' => [
                    ['code' => 'ST101', 'name' => 'Probability Theory', 'credit_hours' => 3],
                    ['code' => 'ST201', 'name' => 'Statistical Inference', 'credit_hours' => 3],
                ],
                'BSc Mathematics (Weekend)' => [
                    ['code' => 'MAW101', 'name' => 'Weekend Calculus', 'credit_hours' => 3],
                    ['code' => 'MAW201', 'name' => 'Weekend Linear Algebra', 'credit_hours' => 3],
                ],
            ],
            'Education Foundations Department' => [
                'BEd Education Science' => [
                    ['code' => 'ED101', 'name' => 'Introduction to Education', 'credit_hours' => 3],
                    ['code' => 'ED201', 'name' => 'Educational Psychology', 'credit_hours' => 3],
                ],
                'BEd Education Science (Weekend)' => [
                    ['code' => 'EDW101', 'name' => 'Intro to Education (Weekend)', 'credit_hours' => 3],
                    ['code' => 'EDW201', 'name' => 'Ed Psychology (Weekend)', 'credit_hours' => 3],
                ],
            ],
        ];

        foreach ($data as $departmentName => $programmes) {
            $department = Department::firstOrCreate([
                'name' => $departmentName,
            ]);

            foreach ($programmes as $programmeName => $courses) {
                $programme = Programme::firstOrCreate([
                    'name' => $programmeName,
                    'department_id' => $department->id,
                ]);

                foreach ($courses as $course) {
                    Course::firstOrCreate([
                        'code' => $course['code'],
                        'programme_id' => $programme->id,
                    ], [
                        'name' => $course['name'],
                        'credit_hours' => $course['credit_hours'],
                    ]);
                }
            }
        }
    }
}
