<?php

namespace App\Jobs;

use App\Models\ScheduleEntry;
use App\Services\GeneticAlgorithm\GADataLoaderService;
use App\Services\GeneticAlgorithm\ScheduleService;
use App\Services\GeneticAlgorithm\TimeSlotGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RunScheduleGeneration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $totalGenerations;
    public function __construct($totalGenerations)
    {
        $this->totalGenerations = $totalGenerations;
    }

    public function handle(): void
    {

        $data = app(GADataLoaderService::class)->loadGAData();

        $venues = $data['venues'];
        $courses = $data['courses'];
        $timeslots = $data['timeslots'];

        TimeSlotGenerator::generate(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);

        $service = new ScheduleService($courses, $venues, TimeSlotGenerator::generate(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']));
        $progress = 0;
        try {
            for ($i = 1; $i <= $this->totalGenerations; $i++) {
                $service->evolvePopulation();
                // Cache progress for polling
                $progress = ($i / $this->totalGenerations) * 100;
                Cache::forever('schedule_generation_progress', [
                    'generation' => $i,
                    'fitness' => $service->getBest()->fitness ?? 0,
                    'progress' => round($progress, 2)
                ]);
                sleep(0.2);
            }
        } finally {
            $entries = $service->getBest()->entries;
            DB::beginTransaction();
            try {
                DB::table('schedule_entries')->truncate();
                foreach ($entries as $entry) {
                    foreach ($entry->timeSlots as $slot) {
                        ScheduleEntry::create([
                            'course_id'    => $entry->course->id,
                            'venue_id'     => $entry->venue->id,
                            'lecturer_id'  => $entry->lecturer,
                            'programme_id' => rand(1, 6),
                            'day'          => $slot['day'],
                            'start_time'   => $slot['start'],
                            'end_time'     => $slot['end'],
                        ]);
                    }
                }
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                report($e);
            }

            Cache::forever('schedule_generation_progress', [
                'generation' => $this->totalGenerations,
                'fitness' => $service->getBest()->fitness ?? 0,
                'progress' => round($progress, 2)
            ]);
        }
    }
}
