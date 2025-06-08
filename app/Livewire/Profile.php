<?php

namespace App\Livewire;

use App\Livewire\Forms\PasswordUpdateForm;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Profile extends Component
{
    use WireUiActions;
    public PasswordUpdateForm $form;

    public function update()
    {
        if ($this->form->update()) {
            $this->notification()->success(
                'Password Updated',
                'Your password has been successfully updated.'
            );
        }
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
