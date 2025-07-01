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
        Schema::create('constraints', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('day');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_hard');
            $table->unsignedBigInteger('constraintable_id');
            $table->string('constraintable_type');
            $table->index(['constraintable_type', 'constraintable_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constraints');
    }
};
