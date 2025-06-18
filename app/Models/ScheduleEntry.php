<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleEntry extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleEntryFactory> */
    use HasFactory;

    protected $fillable = [
        'level',
        'day',
        'start_time',
        'end_time',
        'lecturer_id',
        'programme_id',
        'course_id',
        'venue_id'
    ];

    public function course() : BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}
