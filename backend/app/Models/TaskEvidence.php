<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskEvidence extends Model
{
    use HasFactory;

    protected $table = 'task_evidences';

    protected $fillable = [
        'task_assignment_id',
        'evidence_type',
        'file_path',
        'file_url',
        'file_size',
        'mime_type',
        'description',
        'latitude',
        'longitude',
        'taken_at',
        'uploaded_by',
        'uploaded_at',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'taken_at' => 'datetime',
        'uploaded_at' => 'datetime',
    ];

    const TYPE_PHOTO = 'photo';
    const TYPE_VIDEO = 'video';
    const TYPE_DOCUMENT = 'document';
    const TYPE_SIGNATURE = 'signature';
    const TYPE_AUDIO = 'audio';

    public function taskAssignment(): BelongsTo
    {
        return $this->belongsTo(TaskAssignment::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
