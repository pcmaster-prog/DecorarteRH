<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('access_profile_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('access_profile_id')->constrained('access_profiles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['access_profile_id', 'permission_id']);
            $table->index('access_profile_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_profile_permissions');
    }
};
