<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name("login");

Route::get("/password-assistance", function () {
    return view('auth.password-assistance');
})->name("password-assistance");

Route::get("/account-setup", function () {
    return view('students.account-setup');
})->name("account-setup");

Route::get("/student-dashboard", function () {
    return view('students.student-dashboard');
})->name("student-dashboard");
