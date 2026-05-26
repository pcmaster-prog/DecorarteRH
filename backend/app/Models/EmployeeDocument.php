<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDocument extends Model
{
    use HasFactory;

    protected $table = 'employee_documents';

    protected $fillable = [
        'employee_id',
        'document_type',
        'document_name',
        'file_path',
        'file_url',
        'file_size',
        'mime_type',
        'is_verified',
        'verified_by',
        'verified_at',
        'expires_at',
        'notes',
        'uploaded_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    const TYPE_INE = 'ine';
    const TYPE_CURP = 'curp';
    const TYPE_RFC = 'rfc';
    const TYPE_NSS = 'nss';
    const TYPE_BIRTH_CERT = 'birth_certificate';
    const TYPE_PROOF_ADDRESS = 'proof_address';
    const TYPE_CONTRACT = 'contract';
    const TYPE_SEVERANCE = 'severance';
    const TYPE_RECOMMENDATION = 'recommendation';
    const TYPE_CERTIFICATE = 'certificate';
    const TYPE_PHOTO = 'photo';
    const TYPE_OTHER = 'other';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
