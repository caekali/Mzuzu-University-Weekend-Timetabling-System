<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name("login");

Route::get("/password-assistance", function () {
    return view('auth.password-assistance');
})->name("password-assistance");
