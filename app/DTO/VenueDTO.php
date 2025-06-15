<?php

namespace App\DTO;

class VenueDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public int $capacity
    ) {}
}
