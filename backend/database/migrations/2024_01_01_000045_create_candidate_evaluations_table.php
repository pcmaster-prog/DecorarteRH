<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->cascadeOnDelete();
            $table->string('evaluation_type', 50);
            $table->decimal('score', 8, 2)->nullable();
            $table->decimal('max_score', 8, 2)->default(100);
            $table->decimal('percentage', 5, 2)->nullable();
            $table->boolean('passed')->nullable();
            $table->foreignId('evaluated_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('evaluated_at')->useCurrent();
            $table->text('notes')->nullable();
            $table->json('answers')->nullable();
            $table->timestamps();

            $table->index('candidate_id');
            $table->index('evaluation_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_evaluations');
    }
};
