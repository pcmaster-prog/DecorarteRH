<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->date('date');
            $table->string('type', 30)->default('fixed');
            $table->boolean('is_recurring')->default(true);
            $table->integer('year')->nullable();
            $table->boolean('is_paid')->default(true);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('date');
            $table->index('year');
            $table->index('is_active');
            $table->index('is_recurring');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
