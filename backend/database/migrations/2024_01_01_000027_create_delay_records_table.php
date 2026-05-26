<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delay_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('attendance_log_id')->nullable()->constrained('attendance_logs')->nullOnDelete();
            $table->date('date');
            $table->timestamp('expected_time');
            $table->timestamp('actual_time');
            $table->integer('delay_minutes');
            $table->integer('tolerance_minutes')->default(10);
            $table->boolean('is_converted_to_absence')->default(false);
            $table->foreignId('converted_absence_id')->nullable()->constrained('absence_records')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('employee_id');
            $table->index('date');
            $table->index('is_converted_to_absence');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delay_records');
    }
};
