<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('contract_type', 50);
            $table->string('contract_number', 100)->unique();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('salary', 12, 2)->nullable();
            $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->integer('trial_period_days')->default(30);
            $table->date('trial_end_date')->nullable();
            $table->boolean('is_trial_approved')->nullable();
            $table->foreignId('trial_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('trial_approved_at')->nullable();
            $table->foreignId('template_id')->nullable()->constrained('contract_templates')->nullOnDelete();
            $table->text('content')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->foreignId('signed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamp('terminated_at')->nullable();
            $table->string('termination_reason', 100)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('employee_id');
            $table->index('contract_type');
            $table->index('is_active');
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
