<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{

    public function show()
    {
        return view('contact-admin');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required|min:10',
        ]);

        // Mail::to('admin@university.edu')->send(new \App\Mail\AdminQuery($request->only('name', 'email', 'message')));

        return back()->with('status', 'Your message has been sent to the admin.');
    }
}
