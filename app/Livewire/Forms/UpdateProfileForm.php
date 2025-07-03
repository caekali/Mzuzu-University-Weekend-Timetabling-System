<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateProfileForm extends Form
{

    #[Validate('required|string|max:255')]
    public string $first_name;

    #[Validate('required|string|max:255')]
    public string $last_name;

    #[Validate('required|email')]
    public string $email;

    public ?int $programmeId = null;
    public ?int $level = null;
    public ?int $department = null;

    public function setUser(User $user): void
    {
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        if ($user->isStudent()) {
            $this->programmeId = $user->student->programme->id;
            $this->level =  $user->student->level;
        }

        if ($user->isLecturer()) {
            $this->department = $user->lecturer->department->id;
        }
    }

    public function rules(): array
    {
        $base = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email'
        ];

        $user = Auth::user();


        if ($user->isStudent()) {
            $base['programmeId'] = 'required|exists:programmes,id';
            $base['level'] = 'required|integer|min:1|max:6';
        }

        if ($user->isLecturer()) {
            $base['department'] = 'required|exists:departments,id';
        }

        return $base;
    }

    public function update(): void
    {
        $this->validate();

        $user = Auth::user();

        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;

        $user->email = $this->email;

        if ($user->isStudent()) {
            $student  = Auth::user()->student;

            $student->programme_id = $this->programmeId;
            $student->level = $this->level;
        }
        if ($user->isLecturer()) {
            $lecturer  = Auth::user()->lecturer;
            $lecturer->department_id = $this->department;
        }

        $user->save();
    }
}
