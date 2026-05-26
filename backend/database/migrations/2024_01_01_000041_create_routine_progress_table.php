<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routine_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('routine_assignment_id')->constrained('routine_assignments')->cascadeOnDelete();
            $table->foreignId('routine_task_id')->constrained('routine_tasks')->cascadeOnDelete();
            $table->foreignId('task_assignment_id')->nullable()->constrained('task_assignments')->nullOnDelete();
            $table->string('status', 30)->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('routine_assignment_id');
            $table->index('routine_task_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routine_progress');
    }
};
