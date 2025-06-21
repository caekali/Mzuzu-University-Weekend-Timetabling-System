<?php

use App\Http\Controllers\GAController;
use App\Http\Controllers\RoleSwitchController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\ActivateAccount;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\RequestActivationLink;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Constraint\LecturerConstraints;
use App\Livewire\Constraint\VenueConstraints;
use App\Livewire\ConstraintList;
use App\Livewire\Course\CourseList;
use App\Livewire\CourseAllocation\CourseAllocation;
use App\Livewire\Dashboard;
use App\Livewire\Department\DepartmentList;
use App\Livewire\Profile\Profile;
use App\Livewire\Profile\ProfileSetup;
use App\Livewire\Programme\ProgrammeList;
use App\Livewire\Settings;
use App\Livewire\Timetable\FullTimetable;
use App\Livewire\Timetable\GenerateTimetable;
use App\Livewire\Timetable\PersonalTimetable;
use App\Livewire\Timetable\Timetable;
use App\Livewire\User\UserList;
use App\Livewire\Venue\VenueList;
use App\Models\ScheduleEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/test', function () {
    $entries = ScheduleEntry::with(['course', 'venue', 'lecturer.user'])
        ->orderBy('start_time')
        ->get();

    $grouped = $entries->groupBy(function ($entry) {
        return "{$entry->day}-{$entry->course_id}-{$entry->lecturer_id}";
    });

    $mergedEntries = []; // 🔁 Flat array without group key

    foreach ($grouped as $blocks) {
        $blocks = $blocks->sortBy('start_time')->values();

        $current = $blocks[0];

        for ($i = 1; $i < $blocks->count(); $i++) {
            $next = $blocks[$i];

            if ($next->start_time <= $current->end_time) {
                $current->end_time = max($current->end_time, $next->end_time);
            } else {
                $mergedEntries[] = [
                    'day' => $current->day,
                    'start_time' => $current->start_time,
                    'end_time' => $current->end_time,
                    'level' => $current->level,
                    'course' => $current->course->name ?? '',
                    'lecturer' => $current->lecturer->user->name ?? '',
                    'venue' => $current->venue->name ?? '',
                ];
                $current = $next;
            }
        }

        // Push last one
        $mergedEntries[] = [
            'day' => $current->day,
            'start_time' => $current->start_time,
            'end_time' => $current->end_time,
            'level' => $current->level,
            'course' => $current->course->name ?? '',
            'lecturer' => $current->lecturer->user->name ?? '',
            'venue' => $current->venue->name ?? '',
        ];
    }

    return $mergedEntries;


    return $mergedGroups;
});


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
    Route::get('/profile/setup', ProfileSetup::class)->name('profile.setup');
});


Route::middleware(['auth', 'profile.setup'])->group(function () {
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/my-timetable', PersonalTimetable::class)->name('my.timetable');
    Route::get('/full-timetable', FullTimetable::class)->name('full.timetable');
});

Route::middleware(['auth', 'role:HOD'])->group(function () {
    Route::get('/courses', CourseList::class)->name('courses');
    Route::get('/programmes', ProgrammeList::class)->name('programmes');
    Route::get('/course-allocations', CourseAllocation::class)->name('course-allocations');
    Route::get('/lecturers/constraints', LecturerConstraints::class)->name('lecturer.constraints');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/departments', DepartmentList::class)->name('departments');
    Route::get('/venues', VenueList::class)->name('venues');
    Route::get('/users', UserList::class)->name('users');
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/timetable/generate', GenerateTimetable::class)->name('timetable.generate');
    Route::get('/venues/constraints', VenueConstraints::class)->name('venue.constraints');
});
