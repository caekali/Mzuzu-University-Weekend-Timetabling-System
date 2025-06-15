<?php

namespace App\Jobs;

use App\DTO\GAParameterDTO;
use App\Models\ScheduleEntry;
use App\Services\GeneticAlgorithm\GADataLoaderService;
use App\Services\GeneticAlgorithm\ScheduleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RunScheduleGeneration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle(): void
    {

        $data = app(GADataLoaderService::class)->loadGAData();

        $venues = $data['venues'];
        $courses = $data['courses'];
        $timeslots = $data['timeslots'];

        $parameters = GAParameterDTO::fromDb();

        $service = new ScheduleService(
            $parameters->populationSize,
            $parameters->numberOfGenerations,
            $parameters->tournamentSize,
            $parameters->mutationRate,
            $parameters->crossoverRate,
            $courses,
            $venues,
            $timeslots
        );
        $progress = 0;
        try {
            for ($i = 1; $i <=  $parameters->numberOfGenerations; $i++) {
                $service->evolvePopulation();
                // Cache progress for polling
                $progress = ($i /  $parameters->numberOfGenerations) * 100;
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
                'generation' =>  $parameters->numberOfGenerations,
                'fitness' => $service->getBest()->fitness ?? 0,
                'progress' => round($progress, 2)
            ]);
        }
    }
}
