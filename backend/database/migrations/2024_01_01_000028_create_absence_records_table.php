<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absence_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date');
            $table->string('type', 30)->default('unjustified');
            $table->text('reason')->nullable();
            $table->boolean('is_justified')->default(false);
            $table->foreignId('justification_document_id')->nullable()->constrained('employee_documents')->nullOnDelete();
            $table->boolean('converted_from_delays')->default(false);
            $table->text('notes')->nullable();
            $table->foreignId('registered_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index('employee_id');
            $table->index('date');
            $table->index('type');
            $table->index('converted_from_delays');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absence_records');
    }
};
