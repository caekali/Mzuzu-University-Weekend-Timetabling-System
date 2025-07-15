<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Login extends Component
{
    public $email;

    public $password;

    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required'
    ];

    protected $messages = [
        'email.required' => 'The Email cannot be empty.',
        'email.email' => 'The Email format is not valid.',
        'password.required' => 'The Password cannot be empty.',
    ];

    public function login()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            session()->flash('status', 'We couldn’t find an account with that email.');
            return;
        }

        if (! Hash::check($this->password, $user->password)) {
            $this->addError('general', 'Invalid credentials.');
            return;
        }

        if (!$user->is_active) {
            $user->is_active = true;
            $user->save();
        }

        Auth::login($user, $this->remember);

        $roles = $user->roles->pluck('name');
        session([
            'current_role' => $roles->contains('Lecturer') ? 'Lecturer' : $roles->first()
        ]);

        session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layouts.guest');
    }
}
