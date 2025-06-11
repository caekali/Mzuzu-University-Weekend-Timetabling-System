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
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_hard');
            $table->unsignedBigInteger('constrainable_id');
            $table->string('constrainable_type');
            // Index for faster polymorphic queries
            $table->index(['constrainable_type', 'constrainable_id']);
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
