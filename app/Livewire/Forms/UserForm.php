<?php

namespace App\Livewire\Forms;

use App\Models\Role;
use App\Models\User;
use App\Notifications\SendDefaultPasswordNotification;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    public $userId;
    public $first_name = '';
    public $last_name = '';
    public $email = '';

    public $level = null;

    public $programme_id = null;

    public $department_id = null;

    public array $userRoleIds = [];

    public function store()
    {
        $validated = $this->validate($this->rules());

        $isNew = !$this->userId;
        $user = null;
        if ($isNew) {
            $password = str()->random(10);
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => bcrypt($password),
            ]);

            // Send password via email
            $user->notify(new SendDefaultPasswordNotification($password));
        } else {
            $user = tap(User::findOrFail($this->userId))->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
            ]);
        }


        // current roles
        $oldRoles = $user->roles->pluck('name')->map(fn($r) => strtolower($r))->toArray();

        // new roles
        $newRoles = Role::whereIn('id', $validated['userRoleIds'])->pluck('name')->map(fn($r) => strtolower($r))->toArray();

        // Sync roles
        $user->roles()->sync($validated['userRoleIds']);

        // Handle added roles
        if (in_array('student', $newRoles)) {
            $user->student()->updateOrCreate([], [
                'programme_id' => $this->programme_id,
                'level' => $this->level,
            ]);
        }

        if (in_array('lecturer', $newRoles)) {
            $user->lecturer()->updateOrCreate([], [
                'department_id' => $this->department_id,
            ]);
        }

        // if (in_array('hod', $newRoles)) {
        //     $user->hod()->updateOrCreate([], [
        //         'department_id' => $this->department_id,
        //     ]);
        // }

        // Handle removed roles
        if (!in_array('student', $newRoles) && in_array('student', $oldRoles)) {
            $user->student()->delete();
        }

        if (!in_array('lecturer', $newRoles) && in_array('lecturer', $oldRoles)) {
            $user->lecturer()->delete();
        }

        // if (!in_array('hod', $newRoles) && in_array('hod', $oldRoles)) {
        //     $user->hod()->delete();
        // }

        $this->reset('form');
        $this->resetValidation();
    }

    public function rules()
    {
        $rules = [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'userRoleIds'   => 'required|array|min:1',
        ];

        if ($this->userId) {
            $rules['email'] = [
                'required',
                'email',
                'regex:/^[^@\s]+@(my\.mzuni\.ac\.mw|mzuni\.ac\.mw)$/'
            ];
        } else {
            $rules['email'] = [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[^@\s]+@(my\.mzuni\.ac\.mw|mzuni\.ac\.mw)$/'
            ];
        }


        $roles = Role::whereIn('id', $this->userRoleIds)->pluck('name')->map(fn($r) => strtolower($r));

        if ($roles->contains('student')) {
            $rules['programme_id'] = 'required|exists:programmes,id';
            $rules['level'] = 'required|int';
        }

        if ($roles->contains('lecturer')) {
            $rules['department_id'] = 'required|exists:departments,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'email.regex' => 'Provide instutition email.',
        ];
    }
}
