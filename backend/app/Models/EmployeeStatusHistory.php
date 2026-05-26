<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'employee_status_histories';

    protected $fillable = [
        'employee_id',
        'person_id',
        'from_status',
        'to_status',
        'reason',
        'changed_by',
        'changed_at',
        'notes',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function getFromStatusLabelAttribute(): string
    {
        return (new Person(['status' => $this->from_status]))->status_label ?? $this->from_status;
    }

    public function getToStatusLabelAttribute(): string
    {
        return (new Person(['status' => $this->to_status]))->status_label ?? $this->to_status;
    }
}
