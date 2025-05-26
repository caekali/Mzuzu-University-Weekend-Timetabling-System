<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RoleSwitchController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name("login");
    Route::post('/', [LoginController::class, 'login'])->name("login");
    Route::get('/contact-admin', [ContactController::class, 'show'])->name('contact.admin');
    Route::post('/contact-admin', [ContactController::class, 'send']);
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

Route::prefix("admin")->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});


Route::middleware(['auth'])->group(function () {
    Route::get('profile', function () {
        return view('profile');
    })->name('profile');

    Route::post('profile/update-password', function () {
        return view('profile');
    })->name('profile.update-password');

    Route::get('weekly-timetable', function () {
        return view('weekly-timetable');
    })->name('weekly-timetable');

    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::prefix("lecturer")->middleware(['auth', 'role:Lecturer'])->group(function () {

});

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
