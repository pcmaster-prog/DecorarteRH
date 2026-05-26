<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_historical_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('benefit_type', 50);
            $table->integer('period_year');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->integer('days_generated')->default(0);
            $table->integer('days_taken')->default(0);
            $table->integer('days_paid')->default(0);
            $table->decimal('hours_paid', 8, 2)->default(0);
            $table->decimal('amount_paid', 12, 2)->nullable();
            $table->date('payment_date')->nullable();
            $table->string('status', 30)->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('document_id')->nullable()->constrained('employee_documents')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('published_to_employee')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('employee_id');
            $table->index('benefit_type');
            $table->index('period_year');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_historical_benefits');
    }
};
