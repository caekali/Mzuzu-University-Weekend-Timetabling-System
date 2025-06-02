<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        return view('shared.profile');
    }

    public function  updatePassword() {}

    public function setupAccount(Request $request)
    { // for students

    }
}
