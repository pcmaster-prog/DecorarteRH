<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeVacationBalance;

class VacationBalanceSeeder extends Seeder
{
    public function run(): void
    {
        $balances = [
            // Juan Pérez - 1 año 3 meses = 12 días, tomados 8, pendientes 4
            [
                'employee_id' => 6,
                'period_year' => 2024,
                'days_generated' => 12,
                'days_taken' => 8,
                'days_paid' => 0,
                'days_pending' => 4,
                'days_expired' => 0,
                'vacation_bonus_generated' => 3.0,
                'vacation_bonus_paid' => 3.0,
                'vacation_bonus_pending' => 0,
                'period_start' => '2024-02-01',
                'period_end' => '2025-01-31',
                'deadline_date' => '2025-01-31',
                'notes' => 'Vacaciones parcialmente disfrutadas. 4 días pendientes.',
            ],
            // María González - 2 años 6 meses = 14 días, al corriente
            [
                'employee_id' => 7,
                'period_year' => 2024,
                'days_generated' => 14,
                'days_taken' => 14,
                'days_paid' => 0,
                'days_pending' => 0,
                'days_expired' => 0,
                'vacation_bonus_generated' => 3.5,
                'vacation_bonus_paid' => 3.5,
                'vacation_bonus_pending' => 0,
                'period_start' => '2024-01-15',
                'period_end' => '2025-01-14',
                'deadline_date' => '2025-01-14',
                'notes' => 'Vacaciones al corriente.',
            ],
            // Pedro Ramírez - 8 meses = proporcional, solo para cálculo interno
            [
                'employee_id' => 8,
                'period_year' => 2024,
                'days_generated' => 0,
                'days_taken' => 0,
                'days_paid' => 0,
                'days_pending' => 0,
                'days_expired' => 0,
                'vacation_bonus_generated' => 0,
                'vacation_bonus_paid' => 0,
                'vacation_bonus_pending' => 0,
                'period_start' => '2024-08-01',
                'period_end' => '2025-07-31',
                'deadline_date' => '2025-07-31',
                'notes' => 'Aún no cumple año. Vacaciones proporcionales solo para cálculo interno.',
            ],
            // Sofía Torres - 4 años 8 meses = 18 días, 6 pendientes
            [
                'employee_id' => 9,
                'period_year' => 2024,
                'days_generated' => 18,
                'days_taken' => 12,
                'days_paid' => 0,
                'days_pending' => 6,
                'days_expired' => 0,
                'vacation_bonus_generated' => 4.5,
                'vacation_bonus_paid' => 3.0,
                'vacation_bonus_pending' => 1.5,
                'period_start' => '2024-03-10',
                'period_end' => '2025-03-09',
                'deadline_date' => '2025-03-09',
                'notes' => '6 días de vacaciones pendientes. Prima vacacional parcialmente pagada.',
            ],
            // Diego Morales - Por hora, según configuración
            [
                'employee_id' => 10,
                'period_year' => 2024,
                'days_generated' => 0,
                'days_taken' => 0,
                'days_paid' => 0,
                'days_pending' => 0,
                'days_expired' => 0,
                'vacation_bonus_generated' => 0,
                'vacation_bonus_paid' => 0,
                'vacation_bonus_pending' => 0,
                'period_start' => '2024-01-15',
                'period_end' => '2025-01-14',
                'deadline_date' => '2025-01-14',
                'notes' => 'Empleado por hora. Prestaciones según configuración administrativa.',
            ],
        ];

        foreach ($balances as $balance) {
            EmployeeVacationBalance::create($balance);
        }
    }
}
