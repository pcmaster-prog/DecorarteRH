<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_seasons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('display_name', 200);
            $table->string('type', 30);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('color', 20)->nullable();
            $table->boolean('is_vacation_blocked')->default(false);
            $table->boolean('requires_special_approval')->default(false);
            $table->integer('max_vacation_employees_per_day')->nullable();
            $table->integer('max_vacation_employees_per_area')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('type');
            $table->index('is_active');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_seasons');
    }
};
