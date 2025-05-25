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
        'day',
        'start_time',
        'end_time',
        'lecture_id',
        'venue_id'
    ];

    public function lecture() : BelongsTo
    {
        return $this->belongsTo(Lecture::class);
    }

    public function venue() : BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}
