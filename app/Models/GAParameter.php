<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GAParameter extends Model
{

    protected $fillable = [
        'pupolation_size',
        'generations',
        'mutation_rate',
        'crossover_rate',
        'penalty_hard',
        'penalty_soft',
        'last_updated'
    ];
}
