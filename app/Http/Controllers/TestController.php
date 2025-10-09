<?php

namespace App\Http\Controllers;

use App\Services\GeneticAlgorithm\GADataLoaderService;
use App\Services\GeneticAlgorithm\GeneticAlgorithm;
use App\Services\GeneticAlgorithm\Schedule;
use App\Services\GeneticAlgorithm\ScheduleEntry;

class TestController extends Controller
{

  public function generate()
  {
    $data = app(GADataLoaderService::class)->loadGAData();
    $schedule = Schedule::generateRandomSchedule($data);
    $schedule->getFitness();
   

    // for ($i = 0; $i < 1; $i++) {
    //   $schedule = Schedule::generateRandomSchedule($data);
    //   foreach ($schedule->getScheduleEntries() as $entry) {
    //     print_r($entry); // or format/display
    //   }
    // }
  }
}
