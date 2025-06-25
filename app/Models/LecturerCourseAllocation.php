<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LecturerCourseAllocation extends Model
{

    protected $fillable = ['lecturer_id', 'course_id', 'level'];

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function programmes()
    {
        return $this->belongsToMany(Programme::class, 'allocation_programme', 'allocation_id', 'programme_id');
    }
}
