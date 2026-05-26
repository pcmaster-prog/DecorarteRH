<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_seniority_hour_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->decimal('total_historical_hours', 10, 2)->default(0);
            $table->decimal('hours_per_workday_config', 5, 2)->default(8);
            $table->integer('workdays_per_week_config')->default(6);
            $table->decimal('weeks_per_month_config', 5, 2)->default(4.33);
            $table->integer('months_per_year_config')->default(12);
            $table->string('calculation_method', 30)->default('hours');
            $table->decimal('equivalent_days', 10, 2)->default(0);
            $table->decimal('equivalent_weeks', 10, 2)->default(0);
            $table->decimal('equivalent_months', 10, 2)->default(0);
            $table->decimal('equivalent_years', 10, 2)->default(0);
            $table->string('human_readable_seniority', 255)->nullable();
            $table->string('recognized_seniority_label', 255)->nullable();
            $table->date('real_hire_date')->nullable();
            $table->timestamp('platform_registered_at')->nullable();
            $table->boolean('impacts_vacations')->default(true);
            $table->boolean('impacts_christmas_bonus')->default(true);
            $table->boolean('impacts_profit_sharing')->default(true);
            $table->boolean('impacts_severance')->default(true);
            $table->boolean('impacts_recommendation_letter')->default(true);
            $table->string('status', 30)->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('evidence_document_id')->nullable()->constrained('employee_documents')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->foreignId('reopened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reopened_at')->nullable();
            $table->text('reopen_reason')->nullable();
            $table->timestamps();

            $table->unique('employee_id');
            $table->index('status');
            $table->index('validated_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_seniority_hour_records');
    }
};
