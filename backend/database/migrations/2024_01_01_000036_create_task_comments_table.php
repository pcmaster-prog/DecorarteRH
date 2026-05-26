<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_assignment_id')->constrained('task_assignments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->text('comment');
            $table->boolean('is_internal')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamps();

            $table->index('task_assignment_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_comments');
    }
};
