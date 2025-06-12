<?php

namespace App\Services\GA;

class Crossover
{
   public function crossover(Schedule $parent1, Schedule $parent2): Schedule
    {
        $entries1 = $parent1->getEntries();
        $entries2 = $parent2->getEntries();

        $cutPoint = rand(0, count($entries1) - 1);
        $childEntries = array_merge(
            array_slice($entries1, 0, $cutPoint),
            array_slice($entries2, $cutPoint)
        );

        return new Schedule($childEntries);
    }
}
