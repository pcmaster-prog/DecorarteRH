<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->foreignId('job_vacancy_id')->nullable()->constrained('job_vacancies')->nullOnDelete();
            $table->string('source', 50)->default('portal');
            $table->string('google_id', 255)->nullable()->unique();
            $table->date('application_date')->nullable();
            $table->decimal('evaluation_score', 5, 2)->nullable();
            $table->timestamp('interview_date')->nullable();
            $table->date('trial_start_date')->nullable();
            $table->date('trial_end_date')->nullable();
            $table->decimal('trial_evaluation', 5, 2)->nullable();
            $table->string('status', 50)->default('candidate_registered');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('person_id');
            $table->index('status');
            $table->index('job_vacancy_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
