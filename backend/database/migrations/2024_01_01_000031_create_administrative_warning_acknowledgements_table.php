<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administrative_warning_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warning_id')->constrained('administrative_warnings')->cascadeOnDelete();
            $table->foreignId('acknowledged_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('acknowledged_at')->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->string('device', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('warning_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrative_warning_acknowledgements');
    }
};
