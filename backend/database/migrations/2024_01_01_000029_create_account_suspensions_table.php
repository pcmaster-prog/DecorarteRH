<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_suspensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('triggered_by')->constrained('users')->restrictOnDelete();
            $table->string('trigger_reason', 255);
            $table->integer('absence_count')->default(0);
            $table->integer('delay_count')->default(0);
            $table->timestamp('suspended_at')->useCurrent();
            $table->timestamp('reactivated_at')->nullable();
            $table->foreignId('reactivated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reactivation_reason')->nullable();
            $table->text('reactivation_notes')->nullable();
            $table->text('conversation_summary')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('employee_id');
            $table->index('is_active');
            $table->index('suspended_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_suspensions');
    }
};
