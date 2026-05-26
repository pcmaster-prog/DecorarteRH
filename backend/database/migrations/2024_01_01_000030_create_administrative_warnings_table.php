<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administrative_warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('type', 50);
            $table->string('reason', 255);
            $table->text('description')->nullable();
            $table->foreignId('issued_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('issued_at')->useCurrent();
            $table->string('severity', 20)->default('medium');
            $table->boolean('requires_acknowledgement')->default(true);
            $table->timestamp('acknowledged_at')->nullable();
            $table->foreignId('acknowledged_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('employee_id');
            $table->index('type');
            $table->index('severity');
            $table->index('is_active');
            $table->index('issued_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrative_warnings');
    }
};
