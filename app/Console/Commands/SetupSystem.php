<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupSystem extends Command
{
    protected $signature = 'setup:system';
    protected $description = 'Initial setup for roles and first admin';

    public function handle()
    {
        if (\App\Models\User::count() > 0) {
            $this->warn('System already initialized.');
            return;
        }

        $this->call('db:seed', ['--class' => 'RoleSeeder']);
        $this->call('db:seed', ['--class' => 'AdminSeeder']);
        $this->info('✅ System initialized with default roles and admin user.');
    }
}
