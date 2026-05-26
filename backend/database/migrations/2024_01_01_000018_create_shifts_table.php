<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('display_name', 150);
            $table->time('entry_time');
            $table->time('exit_time');
            $table->integer('meal_duration_minutes')->default(30);
            $table->integer('tolerance_minutes')->default(10);
            $table->integer('days_per_week')->default(6);
            $table->boolean('is_night_shift')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
