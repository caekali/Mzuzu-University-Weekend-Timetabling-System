<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GenerateDatabaseReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $userId;
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function handle()
    {
        $totalGenerations = 1000;


        try {
            for ($i = 1; $i <= $totalGenerations; $i++) {
                // Simulate generation
                sleep(0.2); // or your real GA logic

                // Calculate and store progress
                $progress = ($i / $totalGenerations) * 100;
                Cache::forever("ga_progress_user_{$this->userId}", round($progress, 2));
            }
        } finally {
            // Ensure we always mark as complete
            Cache::forever("ga_progress_user_{$this->userId}", 100);
            Cache::forever("ga_done_user_{$this->userId}", true);
        }
    }
}
