<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccessProfile;
use App\Models\Permission;

class AccessProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            [
                'name' => 'administrador_total',
                'display_name' => 'Administrador Total',
                'description' => 'Acceso completo a todos los módulos y funciones del sistema.',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'gerente_operativo',
                'display_name' => 'Gerente Operativo',
                'description' => 'Gestión operativa, supervisión de equipos, reportes y operación en vivo.',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'rh_operativo',
                'display_name' => 'RH Operativo',
                'description' => 'Gestión de empleados, contratos, vacaciones y cumplimiento laboral.',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'nomina_limitada',
                'display_name' => 'Nómina Limitada',
                'description' => 'Acceso limitado a funciones de nómina y prestaciones.',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'supervisor_basico',
                'display_name' => 'Supervisor Básico',
                'description' => 'Asignación de tareas, revisión de asistencia y reportes de equipo.',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'supervisor_avanzado',
                'display_name' => 'Supervisor Avanzado',
                'description' => 'Gestión completa de equipo, tareas, rutinas, evidencias y operación en vivo.',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'empleado_base',
                'display_name' => 'Empleado Base',
                'description' => 'Acceso a tareas, asistencia, solicitudes y panel personal.',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'empleado_por_hora',
                'display_name' => 'Empleado por Hora',
                'description' => 'Registro de jornada por horas, pausas y tareas asignadas.',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'candidato',
                'display_name' => 'Candidato',
                'description' => 'Acceso al portal de vacantes y proceso de selección.',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'prospecto_prueba',
                'display_name' => 'Prospecto en Prueba',
                'description' => 'Acceso limitado durante periodo de prueba.',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'exempleado',
                'display_name' => 'Exempleado',
                'description' => 'Acceso limitado a documentos personales.',
                'is_active' => true,
                'is_system' => true,
            ],
        ];

        foreach ($profiles as $profile) {
            AccessProfile::create($profile);
        }

        // Asignar permisos a perfiles
        $adminProfile = AccessProfile::where('name', 'administrador_total')->first();
        $allPermissions = Permission::all();
        $adminProfile->permissions()->attach($allPermissions->pluck('id'));

        $gerenteProfile = AccessProfile::where('name', 'gerente_operativo')->first();
        $gerentePermissions = Permission::whereIn('module', [
            'dashboard', 'employees', 'kardex', 'attendance', 'tasks', 'routines',
            'live_operation', 'reports', 'vacancies', 'candidates', 'interviews',
            'org_chart', 'contracts', 'holidays', 'overtime', 'settings',
        ])->whereNotIn('name', [
            'employees.delete', 'kardex.edit_sensitive', 'kardex.view_sensitive',
            'payroll.close', 'payroll.reopen', 'payroll.view_all_salaries',
            'contracts.edit', 'legal_rules.configure', 'settings.configure',
            'audit_logs.delete', 'backups.restore', 'permissions.configure',
            'roles.configure', 'users.delete', 'account_suspensions.reactivate',
            'employee_seniority_hours.edit_validated',
        ])->get();
        $gerenteProfile->permissions()->attach($gerentePermissions->pluck('id'));

        $rhProfile = AccessProfile::where('name', 'rh_operativo')->first();
        $rhPermissions = Permission::whereIn('module', [
            'employees', 'kardex', 'contracts', 'vacation_balances', 'vacation_requests',
            'attendance', 'delays', 'absences', 'account_suspensions', 'administrative_warnings',
            'candidates', 'vacancies', 'interviews', 'reports', 'documents',
            'legal_rules', 'benefits', 'christmas_bonus', 'profit_sharing',
        ])->get();
        $rhProfile->permissions()->attach($rhPermissions->pluck('id'));

        $supervisorProfile = AccessProfile::where('name', 'supervisor_avanzado')->first();
        $supervisorPermissions = Permission::whereIn('module', [
            'dashboard', 'employees', 'attendance', 'tasks', 'routines',
            'live_operation', 'task_evidences', 'reports', 'courses',
        ])->whereNotIn('name', [
            'employees.delete', 'employees.edit',
        ])->get();
        $supervisorProfile->permissions()->attach($supervisorPermissions->pluck('id'));

        $employeeProfile = AccessProfile::where('name', 'empleado_base')->first();
        $employeePermissions = Permission::whereIn('name', [
            'dashboard.view',
            'tasks.view',
            'attendance.register',
            'attendance.view',
            'vacation_requests.view',
            'vacation_requests.create',
            'documents.view',
            'documents.download',
            'courses.view',
            'internal_promotions.view',
        ])->get();
        $employeeProfile->permissions()->attach($employeePermissions->pluck('id'));
    }
}
