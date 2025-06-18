<?php

use App\Http\Controllers\GAController;
use App\Http\Controllers\RoleSwitchController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\ActivateAccount;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\RequestActivationLink;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Course\CourseList;
use App\Livewire\CourseAllocation\CourseAllocation;
use App\Livewire\Dashboard;
use App\Livewire\Department\DepartmentList;
use App\Livewire\Profile\Profile;
use App\Livewire\Profile\ProfileSetup;
use App\Livewire\Programme\ProgrammeList;
use App\Livewire\Settings;
use App\Livewire\Timetable\GenerateTimetable;
use App\Livewire\Timetable\Timetable;
use App\Livewire\User\UserList;
use App\Livewire\Venue\VenueList;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/switch-role/{role}', [RoleSwitchController::class, 'switch'])->name('auth.switch-role');
Route::get('/test', [GAController::class, 'generate']);


Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');

    Route::get('/activate/request', RequestActivationLink::class)->name('activation.request');
    Route::get('/activate/{userId}', ActivateAccount::class)
        ->middleware('signed')
        ->name('activation.form');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

    Route::get('/profile/setup', ProfileSetup::class)->name('profile.setup');
});


Route::middleware(['auth', 'profile.setup'])->group(function () {
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/courses', CourseList::class)->name('courses');
    Route::get('/departments', DepartmentList::class)->name('departments');
    Route::get('/programmes', ProgrammeList::class)->name('programmes');
    Route::get('/venues', VenueList::class)->name('venues');
    Route::get('/users', UserList::class)->name('users');
    Route::get('/timetable', Timetable::class)->name('timetable');
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/timetable/generate', GenerateTimetable::class)->name('timetable.generate');
    Route::get('/course-allocations', CourseAllocation::class)->name('course-allocations');
});
