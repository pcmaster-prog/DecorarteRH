<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rehire_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->integer('min_months_wait')->default(6);
            $table->integer('max_rehire_attempts')->nullable();
            $table->boolean('applies_to_recommended')->default(true);
            $table->boolean('applies_to_not_recommended')->default(false);
            $table->boolean('applies_to_terminated')->default(false);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rehire_policies');
    }
};
