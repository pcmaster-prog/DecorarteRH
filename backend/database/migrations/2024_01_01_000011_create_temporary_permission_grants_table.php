<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temporary_permission_grants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('permission_name', 150);
            $table->string('scope', 50)->default('global');
            $table->foreignId('granted_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('granted_at')->useCurrent();
            $table->timestamp('expires_at');
            $table->text('reason');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('user_id');
            $table->index('permission_name');
            $table->index(['user_id', 'is_active']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temporary_permission_grants');
    }
};
