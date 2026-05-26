<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('display_name', 200);
            $table->text('description')->nullable();
            $table->string('category', 50);
            $table->string('rule_key', 150)->unique();
            $table->json('value');
            $table->string('value_type', 30)->default('json');
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('version')->default(1);
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('category');
            $table->index('rule_key');
            $table->index('is_active');
            $table->index('effective_from');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_rules');
    }
};
