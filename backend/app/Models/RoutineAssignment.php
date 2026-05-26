<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoutineAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'routine_id',
        'employee_id',
        'assigned_by',
        'assigned_date',
        'started_at',
        'completed_at',
        'status',
        'progress_percentage',
        'notes',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_percentage' => 'integer',
    ];

    public function routine(): BelongsTo
    {
        return $this->belongsTo(Routine::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(RoutineProgress::class);
    }
}
