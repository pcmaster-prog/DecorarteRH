<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_breaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('attendance_log_id')->nullable()->constrained('attendance_logs')->nullOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->integer('expected_duration_minutes')->default(30);
            $table->integer('actual_duration_minutes')->nullable();
            $table->boolean('is_exceeded')->default(false);
            $table->integer('exceeded_minutes')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('employee_id');
            $table->index('started_at');
            $table->index('is_exceeded');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_breaks');
    }
};
