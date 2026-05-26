<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacation_request_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('vacation_requests')->cascadeOnDelete();
            $table->foreignId('approver_id')->constrained('users')->restrictOnDelete();
            $table->string('approval_level', 50);
            $table->string('status', 30)->default('pending');
            $table->text('comments')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index('request_id');
            $table->index('approver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacation_request_approvals');
    }
};
