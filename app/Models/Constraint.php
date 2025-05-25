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
        'start_time',
        'end_time',
        'is_hard',
        'note',
        'constrainable_id',
        'constrainable_type'
    ];

    public function constrainable(): MorphTo
    {
        return $this->morphTo();
    }
}
