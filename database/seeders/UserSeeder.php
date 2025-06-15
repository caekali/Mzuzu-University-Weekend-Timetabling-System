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
        $student = User::factory()->create([
            'first_name' => 'Student',
            'last_name' => 'Example',
            'email' => 'student@my.mzuni.ac.mw',
        ]);
        $student->assignRole('Student');
        $lecturer = User::factory()->create([
            'first_name' => 'Lecturer',
            'last_name' => 'Example',
            'email' => 'lecturer@my.mzuni.ac.mw',
        ]);
        $lecturer->assignRole('Lecturer');
        $hod = User::factory()->create([
            'first_name' => 'HOD',
            'last_name' => 'Example',
            'email' => 'hod@my.mzuni.ac.mw',
        ]);
        $hod->assignRole('HOD');
    }
}
