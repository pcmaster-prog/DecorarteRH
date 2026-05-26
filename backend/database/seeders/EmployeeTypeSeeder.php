<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeType;

class EmployeeTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'sueldo_fijo',
                'display_name' => 'Sueldo Fijo',
                'description' => 'Empleado con sueldo mensual fijo',
                'is_hourly' => false,
                'is_salaried' => true,
                'is_contract' => false,
                'is_intern' => false,
                'is_active' => true,
            ],
            [
                'name' => 'por_hora',
                'display_name' => 'Por Hora',
                'description' => 'Empleado contratado por horas efectivamente trabajadas',
                'is_hourly' => true,
                'is_salaried' => false,
                'is_contract' => false,
                'is_intern' => false,
                'is_active' => true,
            ],
            [
                'name' => 'contrato_temporal',
                'display_name' => 'Contrato Temporal',
                'description' => 'Empleado con contrato por tiempo determinado',
                'is_hourly' => false,
                'is_salaried' => true,
                'is_contract' => true,
                'is_intern' => false,
                'is_active' => true,
            ],
            [
                'name' => 'pasante',
                'display_name' => 'Pasante',
                'description' => 'Estudiante en prácticas profesionales',
                'is_hourly' => false,
                'is_salaried' => false,
                'is_contract' => false,
                'is_intern' => true,
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            EmployeeType::create($type);
        }
    }
}
