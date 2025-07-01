<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Constraint extends Model
{
    /** @use HasFactory<\Database\Factories\ConstraintFactory> */
    use HasFactory;

    protected $fillable = [
        'type',
        'day',
        'start_time',
        'end_time',
        'is_hard',
        'constrainable_id',
        'constrainable_type'
    ];

    public function constraintable(): MorphTo
    {
        return $this->morphTo();
    }
}
