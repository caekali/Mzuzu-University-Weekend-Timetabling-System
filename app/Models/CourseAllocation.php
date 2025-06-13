<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAllocation extends Model
{

    protected $fillable = [
        ' course_programme_id',
        'lecturer_id'
    ];
    
}
