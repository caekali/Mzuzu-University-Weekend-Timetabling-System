<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaySchedule extends Model
{
    public $timestamps = false;
    protected $fillable  = [
        'name',
        'enabled',
        'start_time',
        'end_time'
    ];
}
