<?php

namespace App\DTO;

class TimeSlotDTO
{
    public function __construct(
        public string $day,
        public string $start_time,
        public string $end_time,
    ) {}
}
