<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digital_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->morphs('acknowledgeable');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('acknowledged_at')->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->string('device', 100)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('signature_image', 500)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('acknowledged_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_acknowledgements');
    }
};
