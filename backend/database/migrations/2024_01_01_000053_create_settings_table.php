<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 150)->unique();
            $table->json('value');
            $table->string('type', 30)->default('string');
            $table->string('category', 50)->default('general');
            $table->text('description')->nullable();
            $table->boolean('is_editable')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->index('key');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
