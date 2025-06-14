<?php

namespace App\Services\GeneticAlgorithm;

class ScheduleEntry
{
    public $course;
    public $lecturer;
    public $venue;
    public array $timeSlots;

    public function __construct($course, $lecturer, $venue, array $timeSlots)
    {
        $this->course = $course;
        $this->lecturer = $lecturer;
        $this->venue = $venue;
        $this->timeSlots = $timeSlots;
    }
}
