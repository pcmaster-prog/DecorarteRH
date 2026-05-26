<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->nullable()->unique()->constrained('people')->nullOnDelete();
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255)->nullable();
            $table->string('google_id', 255)->nullable()->unique();
            $table->string('avatar', 500)->nullable();
            $table->foreignId('primary_role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('email');
            $table->index('is_active');
            $table->index('google_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
