<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        $adminPassword = env('ADMIN_PASSWORD', 'password');

        if (User::where('email', $adminEmail)->exists()) {
            $this->command->warn("Admin user already exists.");
            return;
        }

        // Create admin user
        $admin = User::create([
            'first_name' => 'System',
            'last_name' => 'Admin',
            'email' => $adminEmail,
            'password' => Hash::make($adminPassword),
        ]);

        $admin->assignRole('Admin');

        $this->command->info("Admin user created: $adminEmail");
    }
}
