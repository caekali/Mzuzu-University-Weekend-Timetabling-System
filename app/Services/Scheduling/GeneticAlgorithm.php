<?php

namespace App\Services\Scheduling;

class GeneticAlgorithm
{
    protected int $populationSize = 50;
    protected int $generations = 100;
    protected float $mutationRate = 0.05;
    protected array $courses;
    protected array $programmes;
    protected array $venues;
    protected array $lecturers;
    protected array $allocations;

    public function __construct()
    {
        $this->courses = [
            ['id' => 1, 'name' => 'Math 101', 'weekly_hours' => 4],
            ['id' => 2, 'name' => 'Physics 101', 'weekly_hours' => 3],
            ['id' => 3, 'name' => 'Chemistry 101', 'weekly_hours' => 3],
            ['id' => 4, 'name' => 'English 101', 'weekly_hours' => 2],
            ['id' => 5, 'name' => 'Biology 101', 'weekly_hours' => 3],
        ];

        $this->venues = [
            ['id' => 1, 'name' => 'Room A'],
            ['id' => 2, 'name' => 'Room B'],
            ['id' => 3, 'name' => 'Room C'],
            ['id' => 4, 'name' => 'Room D'],
        ];

        $this->lecturers = [
            ['id' => 1, 'name' => 'Dr. Smith'],
            ['id' => 2, 'name' => 'Prof. Johnson'],
            ['id' => 3, 'name' => 'Dr. Williams'],
            ['id' => 4, 'name' => 'Dr. Brown'],
            ['id' => 5, 'name' => 'Prof. Davis'],
        ];

        $this->programmes = [
            ['id' => 1, 'name' => 'Computer Science'],
            ['id' => 2, 'name' => 'Physics'],
            ['id' => 3, 'name' => 'Chemistry'],
            ['id' => 4, 'name' => 'Biology'],
            ['id' => 5, 'name' => 'English'],
        ];

        // 20 allocation records: course + lecturer + programme
        $this->allocations = [
            ['course' => $this->courses[0], 'lecturer' => $this->lecturers[0], 'programme' => $this->programmes[0]],
            ['course' => $this->courses[1], 'lecturer' => $this->lecturers[1], 'programme' => $this->programmes[1]],
            ['course' => $this->courses[2], 'lecturer' => $this->lecturers[2], 'programme' => $this->programmes[2]],
            ['course' => $this->courses[3], 'lecturer' => $this->lecturers[3], 'programme' => $this->programmes[4]],
            ['course' => $this->courses[4], 'lecturer' => $this->lecturers[4], 'programme' => $this->programmes[3]],

            ['course' => $this->courses[0], 'lecturer' => $this->lecturers[1], 'programme' => $this->programmes[0]],
            ['course' => $this->courses[1], 'lecturer' => $this->lecturers[2], 'programme' => $this->programmes[1]],
            ['course' => $this->courses[2], 'lecturer' => $this->lecturers[3], 'programme' => $this->programmes[2]],
            ['course' => $this->courses[3], 'lecturer' => $this->lecturers[4], 'programme' => $this->programmes[4]],
            ['course' => $this->courses[4], 'lecturer' => $this->lecturers[0], 'programme' => $this->programmes[3]],

            ['course' => $this->courses[0], 'lecturer' => $this->lecturers[2], 'programme' => $this->programmes[0]],
            ['course' => $this->courses[1], 'lecturer' => $this->lecturers[3], 'programme' => $this->programmes[1]],
            ['course' => $this->courses[2], 'lecturer' => $this->lecturers[4], 'programme' => $this->programmes[2]],
            ['course' => $this->courses[3], 'lecturer' => $this->lecturers[0], 'programme' => $this->programmes[4]],
            ['course' => $this->courses[4], 'lecturer' => $this->lecturers[1], 'programme' => $this->programmes[3]],

            ['course' => $this->courses[0], 'lecturer' => $this->lecturers[3], 'programme' => $this->programmes[0]],
            ['course' => $this->courses[1], 'lecturer' => $this->lecturers[4], 'programme' => $this->programmes[1]],
            ['course' => $this->courses[2], 'lecturer' => $this->lecturers[0], 'programme' => $this->programmes[2]],
            ['course' => $this->courses[3], 'lecturer' => $this->lecturers[1], 'programme' => $this->programmes[4]],
            ['course' => $this->courses[4], 'lecturer' => $this->lecturers[2], 'programme' => $this->programmes[3]],
        ];



        // $this->courses = Course::all()->toArray();
        // $this->venues = Venue::all()->toArray();
        // $this->lecturers = Lecturer::all()->toArray();
        // $this->allocations = LecturerCourseAllocation::all()->toArray();
    }

    public function run()
    {
        $population = new Population(
            $this->courses,
            $this->venues,
            $this->lecturers,
            $this->allocations,
            $this->populationSize,
            $this->mutationRate
        );

        for ($i = 0; $i < $this->generations; $i++) {
            $population->evolve();
        }

        return $population->getFittest();
    }
}
