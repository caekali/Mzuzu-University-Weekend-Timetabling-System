<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleVersion extends Model
{
    protected $fillable = ['label', 'is_published','published_at'];

    public $timestamps = false;

    public function entries()
    {
        return $this->hasMany(ScheduleEntry::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
