<?php

namespace App\Jobs;

use App\DTO\GAParameterDTO;
use App\Models\ScheduleEntry;
use App\Models\ScheduleVersion;
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
    private $versionLabel;

    public function __construct($versionLabel)
    {
        $this->versionLabel = $versionLabel;
    }

    public function handle(): void
    {

        $data = app(GADataLoaderService::class)->loadGAData();
        $parameters = GAParameterDTO::fromDb();
        $ga = new GeneticAlgorithm(
            populationSize: $parameters->populationSize,
            eliteSchedules: $parameters->eliteSchedules,
            crossoverRate: $parameters->crossoverRate,
            mutationRate: $parameters->mutationRate,
            tournamentSize: $parameters->tournamentSize,
            data: $data
        );

        $population = $ga->initializePopulation();
        $bestSchedule = $population->getFittest();
        $progress = 0;
        $i = 1;
        try {
            for (; $bestSchedule->getFitness() != 1.0 && $i <= $parameters->numberOfGenerations; $i++) {
                $population = $ga->evolve($population);
                $bestSchedule = $population->getFittest();

                // Cache progress for polling
                $progress = ($i /  $parameters->numberOfGenerations) * 100;
                Cache::forever('schedule_generation_progress', [
                    'generation' => $i,
                    'fitness' => $bestSchedule->getFitness() ?? 0,
                    'num_of_hard_conflicts' => $bestSchedule->getNumberOfHardConflicts(),
                    'num_of_soft_conflicts' => $bestSchedule->getNumberOfSoftConflicts(),
                    'progress' => round($progress, 2),
                    'isDone' => false
                ]);
            }
        } finally {
            $entries = $bestSchedule->getScheduleEntries();
            DB::beginTransaction();
            try {
                $scheduleVersion = ScheduleVersion::create([
                    'label' => $this->versionLabel,
                    'generated_at' => now(),
                ]);

                foreach ($entries as $entry) {
                    foreach ($entry->timeSlots as $slot) {
                        foreach ($entry->programmes as $programme) {
                            ScheduleEntry::create([
                                'schedule_version_id' => $scheduleVersion->id,
                                'course_id'           => $entry->course->id,
                                'venue_id'            => $entry->venue->id,
                                'lecturer_id'         => $entry->lecturer,
                                'level'               => $entry->level,
                                'programme_id'        => $programme,
                                'day'                 => $slot['day'],
                                'start_time'          => $slot['start'],
                                'end_time'            => $slot['end'],
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
                'num_of_hard_conflicts' => $bestSchedule->getNumberOfHardConflicts(),
                'num_of_soft_conflicts' => $bestSchedule->getNumberOfSoftConflicts(),
                'progress' => round($progress, 2),
                'isDone' => true
            ]);
        }
    }
}
