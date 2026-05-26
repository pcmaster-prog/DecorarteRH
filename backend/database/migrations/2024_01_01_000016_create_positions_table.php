<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('display_name', 150);
            $table->text('description')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->integer('level')->default(1);
            $table->boolean('is_supervisor')->default(false);
            $table->boolean('is_manager')->default(false);
            $table->boolean('is_director')->default(false);
            $table->decimal('base_salary_min', 12, 2)->nullable();
            $table->decimal('base_salary_max', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('department_id');
            $table->index('level');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
