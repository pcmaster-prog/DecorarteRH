<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateDocument extends Model
{
    use HasFactory;

    protected $table = 'candidate_documents';

    protected $fillable = [
        'candidate_id',
        'document_type',
        'document_name',
        'file_path',
        'file_url',
        'file_size',
        'mime_type',
        'is_verified',
        'notes',
        'uploaded_at',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_verified' => 'boolean',
        'uploaded_at' => 'datetime',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
