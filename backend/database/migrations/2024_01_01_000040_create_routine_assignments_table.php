<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routine_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('routine_id')->constrained('routines')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->restrictOnDelete();
            $table->date('assigned_date');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('status', 30)->default('pending');
            $table->integer('progress_percentage')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('routine_id');
            $table->index('employee_id');
            $table->index('status');
            $table->index('assigned_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routine_assignments');
    }
};
