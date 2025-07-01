<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ga_parameters', function (Blueprint $table) {
            $table->id();
            $table->integer("population_size");
            $table->integer('number_of_generations');
            $table->float('mutation_rate');
            $table->float("crossover_rate");
            $table->integer("tournament_size");
            $table->integer("elite_schedules");
            $table->timestamp('last_updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ga_parameters');
    }
};
