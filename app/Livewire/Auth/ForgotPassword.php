<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ForgotPassword extends Component
{

    public string $email = '';

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'We couldn’t find an account with that email.',
            ]);
        }

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', __($status));
        } else {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')->layout('components.layouts.guest');
    }
}
