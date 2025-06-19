<?php

namespace App\DTO;

class CourseDTO
{
    public function __construct(
        public int $id,
        public string $code,
        public string $lecture_hours,
        public int $expected_students,
        public int $lecturer_id,
        public int $level,
        public array $programmes = [],
    ) {}
}
