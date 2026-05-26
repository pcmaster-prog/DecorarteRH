<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('scope', 50)->default('global');
            $table->timestamp('granted_at')->useCurrent();
            $table->foreignId('granted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_restricted')->default(false);
            $table->timestamps();

            $table->index('user_id');
            $table->index(['user_id', 'is_restricted']);
            $table->index('scope');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_user');
    }
};
