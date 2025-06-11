<?php

use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ActivationController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\RoleSwitchController;
use App\Http\Controllers\ConstraintController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimetableController;
use App\Livewire\Auth\ActivateAccount;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\RequestActivationLink;
use App\Livewire\Course\CourseList;
use App\Livewire\Dashboard;
use App\Livewire\Department\DepartmentList;
use App\Livewire\Profile\Profile;
use App\Livewire\Profile\ProfileSetup;
use App\Livewire\Programme\ProgrammeList;
use App\Livewire\Timetable\GenerateTimetable;
use App\Livewire\User\UserList;
use App\Livewire\Venue\VenueList;
use Illuminate\Support\Facades\Auth;


Route::get('/switch-role/{role}', [RoleSwitchController::class, 'switch'])->name('auth.switch-role');


// Laravel + Livewire based views
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::login('/login', Login::class)->name('login');
    Route::get('/activate/request', RequestActivationLink::class)->name('activation.request');
    Route::get('/activate/{userId}', ActivateAccount::class)
        ->middleware('signed')
        ->name('activation.form');
});

Route::middleware('auth')->group(function () {
    // Auth::logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();
    //     return redirect()->route('login');

    Route::get('/profile/setup', ProfileSetup::class)->name('profile.setup');

    Route::middleware('profile.setup')->group(function () {
        Route::get('/profile', Profile::class)->name('profile');
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
    });

    Route::middleware('role:Admin')->group(function () {
        Route::get('/courses', CourseList::class)->name('courses');
        Route::get('/departments', DepartmentList::class)->name('departments');
        Route::get('/programmes', ProgrammeList::class)->name('programmes');
        Route::get('/venues', VenueList::class)->name('venues');
        Route::get('/users', UserList::class)->name('users');
        Route::get('/timetable/generate', GenerateTimetable::class)->name('timetable.generate');
    });
});
