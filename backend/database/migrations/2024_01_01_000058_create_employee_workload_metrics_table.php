<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_workload_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date');
            $table->integer('tasks_assigned')->default(0);
            $table->integer('tasks_completed')->default(0);
            $table->integer('tasks_overdue')->default(0);
            $table->integer('routines_assigned')->default(0);
            $table->integer('routines_completed')->default(0);
            $table->decimal('workload_score', 5, 2)->nullable();
            $table->decimal('efficiency_score', 5, 2)->nullable();
            $table->decimal('punctuality_score', 5, 2)->nullable();
            $table->decimal('attendance_score', 5, 2)->nullable();
            $table->decimal('learning_score', 5, 2)->nullable();
            $table->decimal('reliability_score', 5, 2)->nullable();
            $table->decimal('overall_score', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
            $table->index('employee_id');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_workload_metrics');
    }
};
