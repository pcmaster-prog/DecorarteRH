<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_rule_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rule_id')->constrained('legal_rules')->cascadeOnDelete();
            $table->integer('version')->default(1);
            $table->json('value');
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->text('change_reason')->nullable();
            $table->timestamps();

            $table->index('rule_id');
            $table->index('version');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_rule_versions');
    }
};
