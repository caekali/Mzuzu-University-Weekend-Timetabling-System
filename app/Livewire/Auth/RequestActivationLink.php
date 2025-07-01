<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\SendAccountActivation;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RequestActivationLink extends Component
{
    #[Validate('required|email')]
    public $email;

    public function sendActivationLink()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'We couldn’t find an account with that email.',
            ]);
        }

        if ($user->is_active) {
            session()->flash('status', 'Account is already activated.');
            return;
        }

        // Optional: Rate limit to prevent abuse
        $key = 'activation-request:' . $this->email;
        if (! RateLimiter::tooManyAttempts($key, 3)) {
            RateLimiter::hit($key, 60); // 3 tries per 60 seconds
        } else {
            throw ValidationException::withMessages([
                'email' => 'Too many requests. Try again later.',
            ]);
        }

        // Send Activation Email
        $user->notify(new SendAccountActivation($user));

        session()->flash('status', 'Activation link has been sent to your email.');
    }

    public function render()
    {
        return view('livewire.auth.request-activation-link')->layout('components.layouts.guest');
    }
}
