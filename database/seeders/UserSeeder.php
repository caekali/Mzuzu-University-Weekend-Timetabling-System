<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lecturers = [
            ['first_name' => 'ezekiel', 'last_name' => 'namacha'],
            ['first_name' => 'lome', 'last_name' => 'longwe'],
            ['first_name' => 'chimango', 'last_name' => 'nyasulu'],
            ['first_name' => 'seyani', 'last_name' => 'nayeja'],
            ['first_name' => 'precious', 'last_name' => 'msonda'],
            ['first_name' => 'blessings', 'last_name' => 'ngwira'],
            ['first_name' => 'e.', 'last_name' => "tung'ande"],
            ['first_name' => 'vision', 'last_name' => 'thondoya'],
            ['first_name' => 'josephy', 'last_name' => 'kumwenda'],
            ['first_name' => 'mr.', 'last_name' => 'nalivata'],
            ['first_name' => 'stanley', 'last_name' => 'ndebvu'],
            ['first_name' => 'mwekela', 'last_name' => ''],
            ['first_name' => 'prince', 'last_name' => 'goba'],
            ['first_name' => 'donald', 'last_name' => 'phiri'],
        ];

        foreach ($lecturers as $lecturer) {
            $firstName = Str::title($lecturer['first_name']);
            $lastName = Str::title($lecturer['last_name']);
            $email = strtolower($firstName . '.' . $lastName) . '@my.mzuni.ac.mw';
            $email = str_replace(["'", ' ', '..', '.@'], ['', '', '.', '@'], $email);

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'email_verified_at' => now(),
                    'password' => null, // default password
                    'remember_token' => Str::random(10),
                ]
            );

            $user->assignRole('Lecturer');
        }
    }
}
