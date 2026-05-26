<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->cascadeOnDelete();
            $table->string('document_type', 50);
            $table->string('document_name', 255);
            $table->string('file_path', 500);
            $table->string('file_url', 500)->nullable();
            $table->integer('file_size')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->text('notes')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();

            $table->index('candidate_id');
            $table->index('document_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_documents');
    }
};
