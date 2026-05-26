<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('priority', 20)->default('medium');
            $table->string('status', 30)->default('pending');
            $table->string('category', 50)->nullable();
            $table->integer('estimated_minutes')->nullable();
            $table->boolean('requires_evidence')->default(false);
            $table->string('evidence_type', 30)->nullable();
            $table->text('evidence_instructions')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern', 100)->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('priority');
            $table->index('status');
            $table->index('category');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
