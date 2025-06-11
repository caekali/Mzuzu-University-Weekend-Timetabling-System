<?php

namespace App\Http\Controllers;

use App\Services\Scheduling\GeneticAlgorithm;
use Illuminate\Http\Request;

class GAController extends Controller
{
  public function run()
  {
    $scheduler = new GeneticAlgorithm();
    $bestSchedule = $scheduler->run();

    return response()->json($bestSchedule);
    return view('dashboard', ['best' => $bestSchedule]);
  }
}
