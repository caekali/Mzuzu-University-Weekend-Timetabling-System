<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Admin', 'Student', 'Lecturer', 'HOD']),
        ];
    }

    // Optionally add states for each role
    public function admin()
    {
        return $this->state(fn() => ['name' => 'Admin']);
    }

    public function student()
    {
        return $this->state(fn() => ['name' => 'Student']);
    }

    public function lecturer()
    {
        return $this->state(fn() => ['name' => 'Lecturer']);
    }

    public function hod()
    {
        return $this->state(fn() => ['name' => 'HOD']);
    }
}
