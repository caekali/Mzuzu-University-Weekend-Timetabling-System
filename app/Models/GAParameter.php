<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GAParameter extends Model
{
    protected $table = 'ga_parameters';
    const UPDATED_AT = 'last_updated';
    public $timestamps = false;


    protected $fillable = [
        'population_size',
        'number_of_generations',
        'tournament_size',
        'mutation_rate',
        'crossover_rate',
    ];

    // get parameter from db or create default one and return them
    public static function getOrCreate(): self
    {
        return self::first() ?? self::create([
            'population_size'       => 100,
            'number_of_generations' => 500,
            'tournament_size'       => 5,
            'mutation_rate'         => 0.05,
            'crossover_rate'        => 0.8,
        ]);
    }
}
