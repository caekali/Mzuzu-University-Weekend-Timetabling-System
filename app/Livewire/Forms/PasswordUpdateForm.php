<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PasswordUpdateForm extends Form
{
    #[Validate('required|string')]
    public $current_password;

    #[Validate('required|string|min:8|confirmed')]
    public $password;

    #[Validate('required|string')]
    public $password_confirmation;

    public function update()
    {
        $user = Auth::user();


        $this->validate();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return false;
        }

        $user->update([
            'password' => Hash::make($this->password),
        ]);
      
        $this->reset();
        return true;
    }
}
