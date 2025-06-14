<?php

namespace App\Jobs;

use App\Services\GeneticAlgorithm\GADataLoaderService;
use App\Services\GeneticAlgorithm\ScheduleService;
use App\Services\GeneticAlgorithm\TimeSlotGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

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

        // $venues = $data['venues'];
        // $courses = $data['courses'];
        // $timeslots = $data['timeslots'];



        $service = new ScheduleService(
            $$data['courses'],
            $$data['venues'],
            TimeSlotGenerator::generate(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])
        );

        try {
            for ($i = 1; $i <= $this->totalGenerations; $i++) {
                $service->evolvePopulation();
                // Cache progress
                $progress = ($i / $this->totalGenerations) * 100;
                Cache::forever('schedule_generation_progress', [
                    'generation' => $i,
                    'fitness' => $service->getBest()->fitness ?? 0,
                    'progress' => round($progress, 2)
                ]);
                sleep(0.2);
            }
        } finally {
            // Ensure we always mark as complete
            Cache::forever('schedule_generation_progress', [
                'generation' => $this->totalGenerations,
                'fitness' => $service->getBest()->fitness ?? 0,
                'progress' => round($progress, 2),
                'is_done' => true
            ]);
        }



        // Cache::forget('schedule_generation_progress');

        // $service->getBest();
        // Store best schedule in component
        // $this->bestSchedule = collect($bestSchedule->entries)->map(function ($entry) {
        //     return [
        //         'course' => $entry->course->name,
        //         'lecturer' => $entry->lecturer->name,
        //         'programmes' => implode(', ', collect($entry->course->programmes)->pluck('name')->toArray()),
        //         'day' => $entry->timeSlots[0]['day'] ?? '-',
        //         'start' => $entry->timeSlots[0]['start'] ?? '-',
        //         'end' => end($entry->timeSlots)['start'] + 1 ?? '-',
        //         'venue' => $entry->venue->name,
        //     ];
        // });

        // foreach ($bestSchedule->entries as $entry) {
        //     $savedEntry = new ScheduleEntry([
        //         'schedule_id' => $savedSchedule->id,
        //         'course_id' => $entry->course->id,
        //         'lecturer_id' => $entry->lecturer->id,
        //         'venue_id' => $entry->venue->id,
        //     ]);

        //     $savedEntry->save();

        //     // Attach time slots (assuming pivot table or JSON column)
        //     foreach ($entry->timeSlots as $slot) {
        //         $savedEntry->timeSlots()->create([
        //             'day' => $slot['day'],
        //             'start' => $slot['start'],
        //         ]);
        //     }
        // }
    }
}
