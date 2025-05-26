<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{

    public function show()
    {
        return view('auth.activate-account');
    }

    public function sendActivationLink(Request $request)
    {
        // Mail::to('admin@university.edu')->send(new \App\Mail\AdminQuery($request->only('name', 'email', 'message')));
        return back()->with('status', 'Activation link has been sent to the provided email');
    }
}
