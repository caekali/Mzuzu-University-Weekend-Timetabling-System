<?php

namespace App\Services\GeneticAlgorithm;

use App\Models\Course;
use App\Models\Venue;

class ScheduleEntry
{
    public $course;
    public $venue;
    public $timeSlot;

    public function __construct($course, $venue, $timeSlot)
    {
        // Accept both stdClass and App\Models\Course
        $this->course = $course;
        $this->venue = $venue;
        $this->timeSlot = $timeSlot;
    }
}
