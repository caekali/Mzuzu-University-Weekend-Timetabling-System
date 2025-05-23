<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@example.com',
        ]);
        $admin->roles()->attach(Role::where('name', 'Admin')->first()->id);

        $student = User::factory()->create([
            'first_name' => 'Student',
            'last_name' => 'Student',
            'email' => 'student@example.com',
        ]);

        $student->roles()->attach(Role::where('name', 'Student')->first()->id);

        $lecturer = User::factory()->create([
            'first_name' => 'Lecturer',
            'last_name' => 'Lecturer',
            'email' => 'lecturer@example.com',
        ]);

        $lecturer->roles()->attach(Role::where('name', 'Lecturer')->first()->id);

        $hod = User::factory()->create([
            'first_name' => 'HOD',
            'last_name' => 'HOD',
            'email' => 'hod@example.com',
        ]);

        $hod->roles()->attach(Role::where('name', 'HOD')->first()->id);
    }
}
