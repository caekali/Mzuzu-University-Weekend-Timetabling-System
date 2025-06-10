<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Validate;
use Livewire\Component;

class ResetPassword extends Component
{
    #[Validate('required')]
    public $password;

    #[Validate('required')]
    public $password_confirmation;

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
