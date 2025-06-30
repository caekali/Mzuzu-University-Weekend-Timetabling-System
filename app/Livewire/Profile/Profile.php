<?php

namespace App\Livewire\Profile;

use App\Livewire\Forms\PasswordUpdateForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Profile extends Component
{
    use WireUiActions;
    public PasswordUpdateForm $form;
    public $studentLevel;

    public function mount()
    {
        if (session('current_role') == 'Student') {
            $student =  Auth::user()->student;
            $this->studentLevel = $student->level;
        }
    }

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
        return view('livewire.profile.profile');
    }

    public function updateStudentLevel()
    {
        $student = Auth::user()->student;
        if ($student->level != $this->studentLevel) {
            $student->level = $this->studentLevel;
            $student->save();

            $this->notification()->success(
                'Profile',
                'Profile updated successfully'
            );
        }
    }
}
