<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'administrador_general',
                'display_name' => 'Administrador General',
                'description' => 'Acceso total al sistema, configuración, usuarios, roles, permisos y todas las funciones administrativas.',
                'level' => 100,
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'gerente',
                'display_name' => 'Gerente',
                'description' => 'Gestión operativa, supervisión de supervisores, aprobaciones de nómina, reportes y operación en vivo.',
                'level' => 80,
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'recursos_humanos',
                'display_name' => 'Recursos Humanos / Nómina',
                'description' => 'Gestión de empleados, contratos, nómina, prestaciones, vacaciones, finiquitos y cumplimiento laboral.',
                'level' => 75,
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'supervisor',
                'display_name' => 'Supervisor',
                'description' => 'Asignación de tareas y rutinas, monitoreo de equipo, revisión de evidencias y reportes de área.',
                'level' => 60,
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'empleado',
                'display_name' => 'Empleado',
                'description' => 'Acceso a tareas asignadas, asistencia, solicitudes, vacaciones, cursos y panel personal.',
                'level' => 30,
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'empleado_por_hora',
                'display_name' => 'Empleado por Hora',
                'description' => 'Registro de jornada por horas, pausas, tareas asignadas y panel personal limitado.',
                'level' => 25,
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'prospecto_prueba',
                'display_name' => 'Prospecto en Prueba',
                'description' => 'Acceso limitado durante periodo de prueba en tienda.',
                'level' => 20,
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'candidato',
                'display_name' => 'Candidato',
                'description' => 'Acceso al portal de vacantes, registro, documentos y evaluaciones.',
                'level' => 10,
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'exempleado',
                'display_name' => 'Exempleado',
                'description' => 'Acceso limitado a documentos personales y constancias.',
                'level' => 5,
                'is_active' => true,
                'is_system' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
