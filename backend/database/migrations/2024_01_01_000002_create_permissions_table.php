<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('display_name', 200);
            $table->text('description')->nullable();
            $table->string('module', 100)->index();
            $table->string('action', 50)->index();
            $table->boolean('is_critical')->default(false);
            $table->boolean('requires_approval')->default(false);
            $table->boolean('is_system')->default(false);
            $table->timestamps();

            $table->index('name');
            $table->index('module');
            $table->index('is_critical');
            $table->index(['module', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
