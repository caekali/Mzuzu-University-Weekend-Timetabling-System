<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RoleSwitchController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name("login");
    Route::post('/', [LoginController::class, 'login'])->name("login");
    Route::get('/account-activation', [ContactController::class, 'show'])->name('account-activation');
    Route::post('/account-activation', [ContactController::class, 'sendActivationLink'])->name('account-activation');
});

Route::prefix('password')->group(function () {
    Route::get("/forget-password", function () {
        return view('auth.forget-password');
    })->name("password.forget-password");

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name("logout");
        Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name("change-password");
    });
});

Route::get('/switch-role/{role}', [RoleSwitchController::class, 'switch'])->name('switch.role');

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('programmes', function () {
        return view('admin.programmes.index');
    })->name('admin.programmes');

    Route::get('departments', function () {
        return view('admin.departments.index');
    })->name('admin.departments');

    Route::get('courses', function () {
        return view('admin.courses.index');
    })->name('admin.courses');

    Route::get('users', function () {
        return view('admin.users.index');
    })->name('admin.users');

    Route::get('students', function () {
        return view('admin.users.students');
    })->name('admin.users.students');

    Route::get('lecturers', function () {
        return view('admin.users.lecturers');
    })->name('admin.users.lecturers');


    Route::get('timetable', function () {
        return view('admin.timetable.index');
    })->name('admin.timetable');

    Route::get('timetable/generate', function () {
        return view('admin.timetable.generate');
    })->name('admin.timetable.generate');

    Route::get('constraints', function () {
        return view('admin.constraints.index');
    })->name('admin.constraints');

    Route::get('venues', function () {
        return view('admin.venues.index');
    })->name('admin.venues');
});


Route::middleware(['auth'])->group(function () {
    Route::get('profile', function () {
        return view('shared.profile');
    })->name('profile');

    Route::post('profile/update-password', function () {
        return view('shared.profile');
    })->name('profile.update-password');

    Route::get('weekly-timetable', function () {
        return view('shared.weekly-timetable');
    })->name('weekly-timetable');

    Route::get('dashboard', function () {
        return view('shared.dashboard');
    })->name('dashboard');
});


Route::prefix("lecturer")->middleware(['auth', 'role:Lecturer'])->group(function () {});

Route::prefix("hod")->middleware(['auth', 'role:HOD'])->group(function () {
    Route::get('dashboard', function () {
        return view('hod.dashboard');
    })->name('hod.dashboard');
});

Route::prefix("student")->middleware(['auth', 'role:Student'])->group(function () {
    Route::get("account-setup", function () {
        return view('student.account-setup');
    })->name("student.account-setup");
});
