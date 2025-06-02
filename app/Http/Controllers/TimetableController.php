<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function showTimetableGeneratioPage()
    {
        return view('admin.timetable.generate');
    }
    public function generate() {

    }
}
