<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseProgramme extends Model
{
    protected $fillable = [
        'course_id',
        'programme_id'
    ];
}
