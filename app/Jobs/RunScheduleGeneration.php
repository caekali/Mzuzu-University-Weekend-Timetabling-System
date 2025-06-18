<?php

namespace App\Jobs;

use App\DTO\GAParameterDTO;
use App\Models\ScheduleEntry;
use App\Services\GeneticAlgorithm\GADataLoaderService;
use App\Services\GeneticAlgorithm\GeneticAlgorithm;
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
        $parameters = GAParameterDTO::fromDb();
        $ga = new GeneticAlgorithm(
            populationSize: 10,
            eliteSchedules: 1,
            crossoverRate: 0.8,
            mutationRate: 0.05,
            tournamentSize: 3,
            data: $data
        );

        $population = $ga->initializePopulation();
        $bestSchedule = $population->getFittest();
        $progress = 0;
        try {
            for ($i = 0; $bestSchedule->getFitness() != 1.0 && $i < $parameters->numberOfGenerations; $i++) {
                $population = $ga->evolve($population);
                $bestSchedule = $population->getFittest();

                // Cache progress for polling
                $progress = ($i /  $parameters->numberOfGenerations) * 100;
                Cache::forever('schedule_generation_progress', [
                    'generation' => $i,
                    'fitness' => $bestSchedule->getFitness() ?? 0,
                    'progress' => round($progress, 2),
                    'isDone' => false
                ]);
            }
        } finally {
            $entries = $bestSchedule->getScheduleEntries();
            DB::beginTransaction();
            try {
                DB::table('schedule_entries')->truncate();
                foreach ($entries as $entry) {
                    foreach ($entry->timeSlots as $slot) {
                        foreach ($entry->programmes as $programme) {
                            ScheduleEntry::create([
                                'course_id'    => $entry->course->id,
                                'venue_id'     => $entry->venue->id,
                                'lecturer_id'  => $entry->lecturer,
                                'level'        => $programme,
                                'day'          => $slot['day'],
                                'start_time'   => $slot['start'],
                                'end_time'     => $slot['end'],
                            ]);
                        }
                    }
                }
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                report($e);
            }
            Cache::forever('schedule_generation_progress', [
                'generation' => $i,
                'fitness' => $bestSchedule->getFitness() ?? 0,
                'progress' => round($progress, 2),
                'isDone' => true
            ]);
        }
    }
}
