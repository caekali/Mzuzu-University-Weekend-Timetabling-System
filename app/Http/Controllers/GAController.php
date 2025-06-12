<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Venue;
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


  public function generateSchedule()
  {
    $courses = Course::with(['lecturer', 'programmes'])->get()->all();
    $venues = Venue::all()->all();
    $timeSlots = TimeSlot::all()->all();

    $ga = new GeneticAlgorithm($courses, $venues, $timeSlots);
    $bestSchedule = $ga->run();

    // Convert to viewable format or store in DB
    return response()->json([
      'fitness' => $bestSchedule->fitness,
      'schedule' => $bestSchedule->scheduleEntries,
    ]);
  }
}
