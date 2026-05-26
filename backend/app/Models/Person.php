<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'people';

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone',
        'mobile',
        'date_of_birth',
        'gender',
        'curp',
        'rfc',
        'nss',
        'address',
        'city',
        'state',
        'zip_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'photo_url',
        'status',
        'type',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Estados posibles del Kardex
    const STATUS_VISITOR = 'visitor';
    const STATUS_CANDIDATE_REGISTERED = 'candidate_registered';
    const STATUS_CANDIDATE_DOCS_PENDING = 'candidate_docs_pending';
    const STATUS_CANDIDATE_DOCS_SENT = 'candidate_docs_sent';
    const STATUS_CANDIDATE_STUDYING = 'candidate_studying';
    const STATUS_CANDIDATE_EVAL_PENDING = 'candidate_eval_pending';
    const STATUS_CANDIDATE_EVALUATED = 'candidate_evaluated';
    const STATUS_PROSPECT_INTERVIEW = 'prospect_interview';
    const STATUS_INTERVIEW_REQUESTED = 'interview_requested';
    const STATUS_INTERVIEW_SCHEDULED = 'interview_scheduled';
    const STATUS_INTERVIEWED = 'interviewed';
    const STATUS_TRIAL_STORE = 'trial_store';
    const STATUS_EVAL_PRACTICE = 'eval_practice';
    const STATUS_APPROVED_HIRE = 'approved_hire';
    const STATUS_HIRED_PENDING_SIGN = 'hired_pending_sign';
    const STATUS_HIRED_ACTIVE = 'hired_active';
    const STATUS_EMPLOYEE_ACTIVE = 'employee_active';
    const STATUS_EMPLOYEE_HISTORICAL = 'employee_historical';
    const STATUS_EMPLOYEE_HOURLY = 'employee_hourly';
    const STATUS_EMPLOYEE_TRAINING = 'employee_training';
    const STATUS_EMPLOYEE_PROMOTION_CANDIDATE = 'employee_promotion_candidate';
    const STATUS_EMPLOYEE_PROMOTED = 'employee_promoted';
    const STATUS_ACCOUNT_SUSPENDED = 'account_suspended';
    const STATUS_PENDING_ADMIN_INTERVIEW = 'pending_admin_interview';
    const STATUS_TERMINATION_PROCESS = 'termination_process';
    const STATUS_TERMINATED = 'terminated';
    const STATUS_SEVERANCE_PENDING = 'severance_pending';
    const STATUS_SEVERANCE_CALCULATED = 'severance_calculated';
    const STATUS_SEVERANCE_APPROVED = 'severance_approved';
    const STATUS_SEVERANCE_PAID = 'severance_paid';
    const STATUS_EX_EMPLOYEE_RECOMMENDED = 'ex_employee_recommended';
    const STATUS_EX_EMPLOYEE_NOT_RECOMMENDED = 'ex_employee_not_recommended';
    const STATUS_REHIRE_CANDIDATE = 'rehire_candidate';
    const STATUS_REHIRE_BLOCKED = 'rehire_blocked';
    const STATUS_INACTIVE = 'inactive';

    const TYPE_CANDIDATE = 'candidate';
    const TYPE_EMPLOYEE = 'employee';
    const TYPE_EMPLOYEE_HOURLY = 'employee_hourly';
    const TYPE_EX_EMPLOYEE = 'ex_employee';
    const TYPE_VISITOR = 'visitor';

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function candidate(): HasOne
    {
        return $this->hasOne(Candidate::class);
    }

    public function seniorityHours(): HasMany
    {
        return $this->hasMany(EmployeeSeniorityHourRecord::class, 'employee_id')
            ->through('employee');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(EmployeeStatusHistory::class, 'employee_id')
            ->through('employee');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class, 'employee_id')
            ->through('employee');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_EMPLOYEE_ACTIVE,
            self::STATUS_EMPLOYEE_HOURLY,
            self::STATUS_EMPLOYEE_TRAINING,
            self::STATUS_EMPLOYEE_PROMOTION_CANDIDATE,
            self::STATUS_HIRED_ACTIVE,
        ]);
    }

    public function scopeCandidates($query)
    {
        return $query->where('type', self::TYPE_CANDIDATE);
    }

    public function scopeEmployees($query)
    {
        return $query->whereIn('type', [self::TYPE_EMPLOYEE, self::TYPE_EMPLOYEE_HOURLY]);
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_VISITOR => 'Visitante',
            self::STATUS_CANDIDATE_REGISTERED => 'Candidato registrado',
            self::STATUS_CANDIDATE_DOCS_PENDING => 'Documentos pendientes',
            self::STATUS_CANDIDATE_DOCS_SENT => 'Documentos enviados',
            self::STATUS_CANDIDATE_STUDYING => 'En estudio',
            self::STATUS_CANDIDATE_EVAL_PENDING => 'Evaluación pendiente',
            self::STATUS_CANDIDATE_EVALUATED => 'Evaluado',
            self::STATUS_PROSPECT_INTERVIEW => 'Prospecto por entrevistar',
            self::STATUS_INTERVIEW_REQUESTED => 'Entrevista solicitada',
            self::STATUS_INTERVIEW_SCHEDULED => 'Entrevista agendada',
            self::STATUS_INTERVIEWED => 'Entrevistado',
            self::STATUS_TRIAL_STORE => 'A prueba en tienda',
            self::STATUS_EVAL_PRACTICE => 'En evaluación práctica',
            self::STATUS_APPROVED_HIRE => 'Aprobado para contratación',
            self::STATUS_HIRED_PENDING_SIGN => 'Contratado pendiente de firma',
            self::STATUS_HIRED_ACTIVE => 'Contratado activo',
            self::STATUS_EMPLOYEE_ACTIVE => 'Empleado activo',
            self::STATUS_EMPLOYEE_HISTORICAL => 'Empleado histórico',
            self::STATUS_EMPLOYEE_HOURLY => 'Empleado por hora',
            self::STATUS_EMPLOYEE_TRAINING => 'En capacitación',
            self::STATUS_EMPLOYEE_PROMOTION_CANDIDATE => 'Candidato a promoción',
            self::STATUS_EMPLOYEE_PROMOTED => 'Promovido',
            self::STATUS_ACCOUNT_SUSPENDED => 'Cuenta suspendida',
            self::STATUS_PENDING_ADMIN_INTERVIEW => 'Pendiente de entrevista con administrador',
            self::STATUS_TERMINATION_PROCESS => 'En proceso de baja',
            self::STATUS_TERMINATED => 'Relación laboral terminada',
            self::STATUS_SEVERANCE_PENDING => 'Finiquito pendiente',
            self::STATUS_SEVERANCE_CALCULATED => 'Finiquito calculado',
            self::STATUS_SEVERANCE_APPROVED => 'Finiquito aprobado',
            self::STATUS_SEVERANCE_PAID => 'Finiquito pagado',
            self::STATUS_EX_EMPLOYEE_RECOMMENDED => 'Exempleado recomendable',
            self::STATUS_EX_EMPLOYEE_NOT_RECOMMENDED => 'Exempleado no recomendable',
            self::STATUS_REHIRE_CANDIDATE => 'Candidato a reingreso',
            self::STATUS_REHIRE_BLOCKED => 'Reingreso bloqueado',
            self::STATUS_INACTIVE => 'Baja o inactivo',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            self::STATUS_EMPLOYEE_ACTIVE => 'green',
            self::STATUS_EMPLOYEE_HOURLY => 'blue',
            self::STATUS_EMPLOYEE_TRAINING => 'yellow',
            self::STATUS_ACCOUNT_SUSPENDED => 'red',
            self::STATUS_TERMINATED => 'gray',
            self::STATUS_CANDIDATE_REGISTERED => 'purple',
            self::STATUS_PROSPECT_INTERVIEW => 'orange',
        ];

        return $colors[$this->status] ?? 'gray';
    }
}
