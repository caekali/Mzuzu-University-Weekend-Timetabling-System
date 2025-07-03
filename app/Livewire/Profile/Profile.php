<?php

namespace App\Livewire\Profile;

use App\Livewire\Forms\PasswordUpdateForm;
use App\Models\Department;
use App\Models\Programme;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Profile extends Component
{
    use WireUiActions;

    public PasswordUpdateForm $form;

    public $studentLevel;

    public $isEditingProfile = false;

    public $isEditingPassword = false;

    public $departments = [];

    public $programmes = [];

    public function mount()
    {

        if (session('current_role') == 'Student') {
            $student =  Auth::user()->student;
            $this->studentLevel = $student->level;
        }

        if (session('current_role') === 'Lecturer') {
            $this->departments = Department::all();
        }

        if (session('current_role') === 'Student') {
            $this->programmes = Programme::all();
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

    public function toggleEditProfile()
    {
        $this->isEditingProfile = !$this->isEditingProfile;
    }

    public function toggleEditPassword()
    {
        $this->isEditingPassword = !$this->isEditingPassword;
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

    public function render()
    {
        return view('livewire.profile.profile');
    }
}
