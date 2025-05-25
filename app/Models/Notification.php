<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $fillable = [
        'user_id',
        'role_id',
        'programme_id',
        'level',
        'title',
        'massage'
    ];
}
