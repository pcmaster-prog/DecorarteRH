<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->string('employee_number', 50)->unique();
            $table->foreignId('employee_type_id')->nullable()->constrained('employee_types')->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->date('hire_date')->nullable();
            $table->timestamp('platform_registered_at')->nullable();
            $table->date('termination_date')->nullable();
            $table->string('termination_reason', 100)->nullable();
            $table->decimal('base_salary', 12, 2)->nullable();
            $table->string('salary_type', 20)->default('monthly');
            $table->json('work_schedule')->nullable();
            $table->string('rest_day', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('person_id');
            $table->index('employee_number');
            $table->index('department_id');
            $table->index('supervisor_id');
            $table->index('position_id');
            $table->index('shift_id');
            $table->index('is_active');
            $table->index('hire_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
