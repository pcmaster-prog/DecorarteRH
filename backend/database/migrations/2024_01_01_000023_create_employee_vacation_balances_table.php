<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_vacation_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->integer('period_year');
            $table->integer('days_generated')->default(0);
            $table->integer('days_taken')->default(0);
            $table->integer('days_paid')->default(0);
            $table->integer('days_pending')->default(0);
            $table->integer('days_expired')->default(0);
            $table->decimal('vacation_bonus_generated', 12, 2)->default(0);
            $table->decimal('vacation_bonus_paid', 12, 2)->default(0);
            $table->decimal('vacation_bonus_pending', 12, 2)->default(0);
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->date('deadline_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['employee_id', 'period_year']);
            $table->index('employee_id');
            $table->index('period_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_vacation_balances');
    }
};
