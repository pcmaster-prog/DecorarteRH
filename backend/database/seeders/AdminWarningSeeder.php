<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdministrativeWarning;

class AdminWarningSeeder extends Seeder
{
    public function run(): void
    {
        // Llamada de atención para Pedro Ramírez por acumulación de faltas
        AdministrativeWarning::create([
            'employee_id' => 8,
            'type' => 'suspension',
            'reason' => 'Acumulación de 3 faltas injustificadas',
            'description' => 'El empleado ha acumulado 3 faltas injustificadas en un periodo de 7 días. Se activa suspensión preventiva de cuenta y se requiere entrevista con administrador o gerente para reactivación.',
            'issued_by' => 1,
            'issued_at' => now()->subDays(1),
            'severity' => 'critical',
            'requires_acknowledgement' => true,
            'is_active' => true,
            'notes' => 'Generada automáticamente por el sistema al detectar 3 faltas acumuladas.',
        ]);

        // Llamada de atención para Juan Pérez por retardo
        AdministrativeWarning::create([
            'employee_id' => 6,
            'type' => 'delay',
            'reason' => 'Retardo en entrada',
            'description' => 'El empleado registró entrada con 10 minutos de retardo. Se acumula 1 retardo.',
            'issued_by' => 5,
            'issued_at' => now(),
            'severity' => 'low',
            'requires_acknowledgement' => false,
            'is_active' => true,
            'notes' => 'Primer retardo acumulado. 2 retardos más equivalen a 1 falta.',
        ]);
    }
}
