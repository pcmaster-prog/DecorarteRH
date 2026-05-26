<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeHistoricalBenefit;

class HistoricalBenefitSeeder extends Seeder
{
    public function run(): void
    {
        $benefits = [
            // Juan Pérez - Aguinaldo 2024 pagado
            [
                'employee_id' => 6,
                'benefit_type' => 'christmas_bonus',
                'period_year' => 2024,
                'period_start' => '2024-01-01',
                'period_end' => '2024-12-31',
                'days_generated' => 15,
                'days_taken' => 0,
                'days_paid' => 15,
                'amount_paid' => 4250.00,
                'payment_date' => '2024-12-15',
                'status' => 'paid',
                'notes' => 'Aguinaldo 2024 pagado completo.',
                'created_by' => 1,
                'validated_by' => 1,
                'published_to_employee' => true,
                'published_at' => '2024-12-15',
            ],
            // Juan Pérez - PTU 2024 en revisión
            [
                'employee_id' => 6,
                'benefit_type' => 'profit_sharing',
                'period_year' => 2024,
                'period_start' => '2024-01-01',
                'period_end' => '2024-12-31',
                'days_generated' => 0,
                'days_taken' => 0,
                'days_paid' => 0,
                'amount_paid' => null,
                'payment_date' => null,
                'status' => 'in_review',
                'notes' => 'PTU 2024 pendiente de validación contable.',
                'created_by' => 1,
                'validated_by' => null,
                'published_to_employee' => false,
                'published_at' => null,
            ],
            // María González - Aguinaldo 2024 pagado
            [
                'employee_id' => 7,
                'benefit_type' => 'christmas_bonus',
                'period_year' => 2024,
                'period_start' => '2024-01-01',
                'period_end' => '2024-12-31',
                'days_generated' => 15,
                'days_taken' => 0,
                'days_paid' => 15,
                'amount_paid' => 4750.00,
                'payment_date' => '2024-12-15',
                'status' => 'paid',
                'notes' => 'Aguinaldo 2024 pagado completo.',
                'created_by' => 1,
                'validated_by' => 1,
                'published_to_employee' => true,
                'published_at' => '2024-12-15',
            ],
            // María González - PTU 2024 pagado
            [
                'employee_id' => 7,
                'benefit_type' => 'profit_sharing',
                'period_year' => 2024,
                'period_start' => '2024-01-01',
                'period_end' => '2024-12-31',
                'days_generated' => 0,
                'days_taken' => 0,
                'days_paid' => 0,
                'amount_paid' => 3200.00,
                'payment_date' => '2024-05-15',
                'status' => 'paid',
                'notes' => 'PTU 2024 pagado.',
                'created_by' => 1,
                'validated_by' => 1,
                'published_to_employee' => true,
                'published_at' => '2024-05-15',
            ],
            // Sofía Torres - Aguinaldo 2024 pagado
            [
                'employee_id' => 9,
                'benefit_type' => 'christmas_bonus',
                'period_year' => 2024,
                'period_start' => '2024-01-01',
                'period_end' => '2024-12-31',
                'days_generated' => 15,
                'days_taken' => 0,
                'days_paid' => 15,
                'amount_paid' => 6000.00,
                'payment_date' => '2024-12-15',
                'status' => 'paid',
                'notes' => 'Aguinaldo 2024 pagado completo.',
                'created_by' => 1,
                'validated_by' => 1,
                'published_to_employee' => true,
                'published_at' => '2024-12-15',
            ],
            // Sofía Torres - PTU 2024 pendiente
            [
                'employee_id' => 9,
                'benefit_type' => 'profit_sharing',
                'period_year' => 2024,
                'period_start' => '2024-01-01',
                'period_end' => '2024-12-31',
                'days_generated' => 0,
                'days_taken' => 0,
                'days_paid' => 0,
                'amount_paid' => null,
                'payment_date' => null,
                'status' => 'pending',
                'notes' => 'PTU 2024 pendiente de validar.',
                'created_by' => 1,
                'validated_by' => null,
                'published_to_employee' => false,
                'published_at' => null,
            ],
        ];

        foreach ($benefits as $benefit) {
            EmployeeHistoricalBenefit::create($benefit);
        }
    }
}
