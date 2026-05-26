<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            [
                'name' => 'administrador_general',
                'display_name' => 'Administrador General',
                'description' => 'Responsable de la dirección y administración total de la empresa',
                'department_id' => 1,
                'level' => 100,
                'is_supervisor' => false,
                'is_manager' => false,
                'is_director' => true,
                'is_active' => true,
            ],
            [
                'name' => 'gerente',
                'display_name' => 'Gerente',
                'description' => 'Responsable de la gestión operativa y supervisión de supervisores',
                'department_id' => 2,
                'level' => 80,
                'is_supervisor' => false,
                'is_manager' => true,
                'is_director' => false,
                'is_active' => true,
            ],
            [
                'name' => 'supervisor',
                'display_name' => 'Supervisor',
                'description' => 'Responsable de supervisar ayudantes integrales y empleados por hora',
                'department_id' => null,
                'level' => 60,
                'is_supervisor' => true,
                'is_manager' => false,
                'is_director' => false,
                'is_active' => true,
            ],
            [
                'name' => 'ayudante_integral',
                'display_name' => 'Ayudante Integral',
                'description' => 'Responsable de tareas generales de área, reposición, limpieza y atención',
                'department_id' => null,
                'level' => 30,
                'is_supervisor' => false,
                'is_manager' => false,
                'is_director' => false,
                'is_active' => true,
            ],
            [
                'name' => 'cajero',
                'display_name' => 'Cajero/a',
                'description' => 'Responsable de operaciones de caja y cobro',
                'department_id' => 3,
                'level' => 30,
                'is_supervisor' => false,
                'is_manager' => false,
                'is_director' => false,
                'is_active' => true,
            ],
            [
                'name' => 'almacenista',
                'display_name' => 'Almacenista',
                'description' => 'Responsable de almacén, inventario y recepción de mercancía',
                'department_id' => 4,
                'level' => 30,
                'is_supervisor' => false,
                'is_manager' => false,
                'is_director' => false,
                'is_active' => true,
            ],
            [
                'name' => 'operario_pesaje',
                'display_name' => 'Operario de Pesaje',
                'description' => 'Responsable de pesaje y empaque de productos',
                'department_id' => 4,
                'level' => 30,
                'is_supervisor' => false,
                'is_manager' => false,
                'is_director' => false,
                'is_active' => true,
            ],
            [
                'name' => 'apoyo_por_hora',
                'display_name' => 'Apoyo por Hora',
                'description' => 'Empleado contratado por horas para apoyo en áreas específicas',
                'department_id' => null,
                'level' => 25,
                'is_supervisor' => false,
                'is_manager' => false,
                'is_director' => false,
                'is_active' => true,
            ],
            [
                'name' => 'prospecto_prueba',
                'display_name' => 'Prospecto en Prueba',
                'description' => 'Candidato en periodo de prueba en tienda',
                'department_id' => null,
                'level' => 20,
                'is_supervisor' => false,
                'is_manager' => false,
                'is_director' => false,
                'is_active' => true,
            ],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}
