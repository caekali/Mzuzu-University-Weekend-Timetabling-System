<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ], [
            'email.exists' => 'We couldn’t find an account with that email address.',
        ]);

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

        return redirect()->intended('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
