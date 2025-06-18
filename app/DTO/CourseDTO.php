<?php

namespace App\DTO;

class CourseDTO
{
    public function __construct(
        public int $id,
        public string $code,
        public int $weekly_hours,
        public int $expected_students,
        public int $lecturer_id,
        public array $programmes = [],
    ) {}
}