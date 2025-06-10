<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Livewire\Attributes\Validate;

class Login extends Component
{
    #[Validate('required|email|exists:users,email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    public $remember = false;

    public function login()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        if (! $user->is_active) {
            session()->flash('status', 'Your account is not activated. Activate now.');
            return;
        }

        if (! Hash::check($this->password, $user->password)) {
            $this->addError('general', 'Invalid credentials.');
            return;
        }

        Auth::login($user, $this->remember);

        $roles = $user->roles->pluck('name');
        session([
            'current_role' => $roles->contains('Lecturer') ? 'Lecturer' : $roles->first()
        ]);

        session()->regenerate();

        if ($user->hasRole('Student')) {
            $student = $user->student;
        } else if ($user->hasRole('Lecturer')) {
            $lecturer = $user->lecturer;
        }

        return redirect()->intended('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layouts.guest');
    }
}
