<?php

namespace App\Livewire\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Str;

class ResetPassword extends Component
{

    #[Validate('required')]
    public $token = '';

    #[Validate('required|email|exists:users,email')]
    public $email = '';

    #[Validate('required|min:8|confirmed')]
    public $password = '';

    #[Validate('required')]
    public $password_confirmation = '';

    public function mount($token, Request $request)
    {
        $this->token = $token;
        $this->email = $request->query('email');
    }

    public function resetPassword()
    {
        $this->validate();

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

    
        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', __($status));
            return redirect()->route('login');
        } else {
            $this->addError('error',  __($status));
        }
    }


    public function render()
    {
        return view('livewire.auth.reset-password')->layout('components.layouts.guest');
    }
}
