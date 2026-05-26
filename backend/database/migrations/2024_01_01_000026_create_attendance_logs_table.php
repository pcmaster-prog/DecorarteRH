<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date');
            $table->timestamp('entry_time')->nullable();
            $table->timestamp('meal_start_time')->nullable();
            $table->timestamp('meal_end_time')->nullable();
            $table->timestamp('exit_time')->nullable();
            $table->timestamp('expected_entry')->nullable();
            $table->timestamp('expected_exit')->nullable();
            $table->integer('tolerance_minutes')->default(10);
            $table->boolean('is_delay')->default(false);
            $table->integer('delay_minutes')->default(0);
            $table->boolean('is_absence')->default(false);
            $table->string('absence_type', 30)->nullable();
            $table->boolean('is_early_leave')->default(false);
            $table->integer('early_leave_minutes')->default(0);
            $table->boolean('is_holiday')->default(false);
            $table->boolean('is_overtime')->default(false);
            $table->integer('overtime_minutes')->default(0);
            $table->decimal('worked_hours', 5, 2)->default(0);
            $table->decimal('effective_hours', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('registered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('device', 100)->nullable();
            $table->string('location', 255)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
            $table->index('employee_id');
            $table->index('date');
            $table->index('is_delay');
            $table->index('is_absence');
            $table->index(['employee_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
