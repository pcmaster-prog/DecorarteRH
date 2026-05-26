<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LegalRule;

class LegalRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            // Workday
            [
                'name' => 'Jornada Diaria Base',
                'display_name' => 'Jornada Diaria Base',
                'description' => 'Duración de la jornada laboral diaria en horas',
                'category' => 'workday',
                'rule_key' => 'workday_hours',
                'value' => 8,
                'value_type' => 'decimal',
                'effective_from' => '2024-01-01',
                'is_active' => true,
            ],
            [
                'name' => 'Días Laborables por Semana',
                'display_name' => 'Días Laborables por Semana',
                'description' => 'Cantidad de días laborables en una semana',
                'category' => 'workweek',
                'rule_key' => 'workdays_per_week',
                'value' => 6,
                'value_type' => 'integer',
                'effective_from' => '2024-01-01',
                'is_active' => true,
            ],
            // Tolerance
            [
                'name' => 'Tolerancia Operativa',
                'display_name' => 'Tolerancia Operativa de Entrada',
                'description' => 'Minutos de tolerancia para llegada antes de considerar retardo',
                'category' => 'delay',
                'rule_key' => 'tolerance_minutes',
                'value' => 10,
                'value_type' => 'integer',
                'effective_from' => '2024-01-01',
                'is_active' => true,
            ],
            // Delays to Absence
            [
                'name' => 'Retardos a Falta',
                'display_name' => 'Conversión Retardos a Falta',
                'description' => 'Cantidad de retardos que equivalen a una falta',
                'category' => 'delay',
                'rule_key' => 'delays_to_absence',
                'value' => 3,
                'value_type' => 'integer',
                'effective_from' => '2024-01-01',
                'is_active' => true,
            ],
            // Absences to Suspension
            [
                'name' => 'Faltas a Suspensión',
                'display_name' => 'Faltas para Suspensión Preventiva',
                'description' => 'Cantidad de faltas que generan suspensión preventiva de cuenta',
                'category' => 'suspension',
                'rule_key' => 'absences_to_suspension',
                'value' => 3,
                'value_type' => 'integer',
                'effective_from' => '2024-01-01',
                'is_active' => true,
            ],
            // Meal
            [
                'name' => 'Duración de Comida',
                'display_name' => 'Duración de la Comida',
                'description' => 'Minutos de duración de la comida',
                'category' => 'meal',
                'rule_key' => 'meal_duration_minutes',
                'value' => 30,
                'value_type' => 'integer',
                'effective_from' => '2024-01-01',
                'is_active' => true,
            ],
            // Vacation Table
            [
                'name' => 'Tabla de Vacaciones',
                'display_name' => 'Tabla de Días de Vacaciones',
                'description' => 'Días de vacaciones según antigüedad en años',
                'category' => 'vacation',
                'rule_key' => 'vacation_table',
                'value' => [
                    '1' => 12, '2' => 14, '3' => 16, '4' => 18, '5' => 20,
                    '6' => 22, '7' => 22, '8' => 22, '9' => 22, '10' => 22,
                    '11' => 24, '12' => 24, '13' => 24, '14' => 24, '15' => 24,
                    '16' => 26, '17' => 26, '18' => 26, '19' => 26, '20' => 26,
                ],
                'value_type' => 'json',
                'effective_from' => '2024-01-01',
                'is_active' => true,
            ],
            // Christmas Bonus
            [
                'name' => 'Días de Aguinaldo',
                'display_name' => 'Días de Aguinaldo',
                'description' => 'Días de salario para aguinaldo',
                'category' => 'christmas_bonus',
                'rule_key' => 'christmas_bonus_days',
                'value' => 15,
                'value_type' => 'integer',
                'effective_from' => '2024-01-01',
                'is_active' => true,
            ],
            // Vacation Bonus
            [
                'name' => 'Prima Vacacional',
                'display_name' => 'Porcentaje de Prima Vacacional',
                'description' => 'Porcentaje del salario correspondiente a prima vacacional',
                'category' => 'vacation_bonus',
                'rule_key' => 'vacation_bonus_percent',
                'value' => 25,
                'value_type' => 'integer',
                'effective_from' => '2024-01-01',
                'is_active' => true,
            ],
            // Rehire
            [
                'name' => 'Meses Mínimos para Reingreso',
                'display_name' => 'Meses Mínimos para Reingreso',
                'description' => 'Meses mínimos de espera para reingreso',
                'category' => 'rehire',
                'rule_key' => 'rehire_min_months',
                'value' => 6,
                'value_type' => 'integer',
                'effective_from' => '2024-01-01',
                'is_active' => true,
            ],
            // Workweek Reduction
            [
                'name' => 'Reducción de Jornada 2026',
                'display_name' => 'Reducción Gradual de Jornada 2026',
                'description' => 'Horas semanales para el año 2026',
                'category' => 'workweek',
                'rule_key' => 'workweek_hours_2026',
                'value' => 48,
                'value_type' => 'integer',
                'effective_from' => '2026-01-01',
                'effective_to' => '2026-12-31',
                'is_active' => true,
            ],
            [
                'name' => 'Reducción de Jornada 2027',
                'display_name' => 'Reducción Gradual de Jornada 2027',
                'description' => 'Horas semanales para el año 2027',
                'category' => 'workweek',
                'rule_key' => 'workweek_hours_2027',
                'value' => 46,
                'value_type' => 'integer',
                'effective_from' => '2027-01-01',
                'effective_to' => '2027-12-31',
                'is_active' => true,
            ],
            [
                'name' => 'Reducción de Jornada 2028',
                'display_name' => 'Reducción Gradual de Jornada 2028',
                'description' => 'Horas semanales para el año 2028',
                'category' => 'workweek',
                'rule_key' => 'workweek_hours_2028',
                'value' => 44,
                'value_type' => 'integer',
                'effective_from' => '2028-01-01',
                'effective_to' => '2028-12-31',
                'is_active' => true,
            ],
            [
                'name' => 'Reducción de Jornada 2029',
                'display_name' => 'Reducción Gradual de Jornada 2029',
                'description' => 'Horas semanales para el año 2029',
                'category' => 'workweek',
                'rule_key' => 'workweek_hours_2029',
                'value' => 42,
                'value_type' => 'integer',
                'effective_from' => '2029-01-01',
                'effective_to' => '2029-12-31',
                'is_active' => true,
            ],
            [
                'name' => 'Reducción de Jornada 2030',
                'display_name' => 'Reducción Gradual de Jornada 2030',
                'description' => 'Horas semanales para el año 2030',
                'category' => 'workweek',
                'rule_key' => 'workweek_hours_2030',
                'value' => 40,
                'value_type' => 'integer',
                'effective_from' => '2030-01-01',
                'effective_to' => '2030-12-31',
                'is_active' => true,
            ],
        ];

        foreach ($rules as $rule) {
            LegalRule::create($rule);
        }
    }
}
