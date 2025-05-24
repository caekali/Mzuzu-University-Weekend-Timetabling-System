<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function showForgetPasswordForm()
    {
        return view('auth.forget-password');
    }

    public function setPasswordResetLink()
    {
        return back()->with('status', 'Your message has been sent to the admin.');
    }
}
