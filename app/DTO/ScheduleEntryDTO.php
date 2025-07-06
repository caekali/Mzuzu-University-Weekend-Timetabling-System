<?php

namespace App\DTO;

class ScheduleEntryDTO
{
    public function __construct(
        public readonly \App\Models\Course $course,
        public readonly int $lecturer_id,
        public readonly int $venue_id,
        public readonly array $timeslot, // ['day' => ..., 'start' => ...]
    ) {}
}
