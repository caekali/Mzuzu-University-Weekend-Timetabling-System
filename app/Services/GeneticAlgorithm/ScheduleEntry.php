<?php

namespace App\Services\GeneticAlgorithm;

class ScheduleEntry
{
    public $course;
    public $lecturer;
    public $venue;
    public array $timeSlots = [];
    public array $programmes =  [];

    public function __construct($course, $lecturer, $venue, $timeslots, $programmes)
    {
        $this->course = $course;
        $this->lecturer = $lecturer;
        $this->venue = $venue;
        $this->timeSlots = $timeslots;
        $this->programmes = $programmes;
    }
}
