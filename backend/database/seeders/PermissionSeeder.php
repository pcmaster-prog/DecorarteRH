<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'Ver Dashboard', 'module' => 'dashboard', 'action' => 'view', 'is_critical' => false],
            ['name' => 'dashboard.admin', 'display_name' => 'Dashboard Administrativo', 'module' => 'dashboard', 'action' => 'view', 'is_critical' => false],

            // Users
            ['name' => 'users.view', 'display_name' => 'Ver Usuarios', 'module' => 'users', 'action' => 'view', 'is_critical' => false],
            ['name' => 'users.create', 'display_name' => 'Crear Usuarios', 'module' => 'users', 'action' => 'create', 'is_critical' => false],
            ['name' => 'users.edit', 'display_name' => 'Editar Usuarios', 'module' => 'users', 'action' => 'edit', 'is_critical' => false],
            ['name' => 'users.delete', 'display_name' => 'Eliminar Usuarios', 'module' => 'users', 'action' => 'delete', 'is_critical' => true],
            ['name' => 'users.suspend', 'display_name' => 'Suspender Usuarios', 'module' => 'users', 'action' => 'suspend', 'is_critical' => false],

            // Roles
            ['name' => 'roles.view', 'display_name' => 'Ver Roles', 'module' => 'roles', 'action' => 'view', 'is_critical' => false],
            ['name' => 'roles.create', 'display_name' => 'Crear Roles', 'module' => 'roles', 'action' => 'create', 'is_critical' => false],
            ['name' => 'roles.edit', 'display_name' => 'Editar Roles', 'module' => 'roles', 'action' => 'edit', 'is_critical' => false],
            ['name' => 'roles.configure', 'display_name' => 'Configurar Roles', 'module' => 'roles', 'action' => 'configure', 'is_critical' => true],

            // Permissions
            ['name' => 'permissions.view', 'display_name' => 'Ver Permisos', 'module' => 'permissions', 'action' => 'view', 'is_critical' => false],
            ['name' => 'permissions.configure', 'display_name' => 'Configurar Permisos', 'module' => 'permissions', 'action' => 'configure', 'is_critical' => true],
            ['name' => 'permissions.grant', 'display_name' => 'Otorgar Permisos', 'module' => 'permissions', 'action' => 'assign', 'is_critical' => false],
            ['name' => 'permissions.revoke', 'display_name' => 'Revocar Permisos', 'module' => 'permissions', 'action' => 'delete', 'is_critical' => false],

            // Employees
            ['name' => 'employees.view', 'display_name' => 'Ver Empleados', 'module' => 'employees', 'action' => 'view', 'is_critical' => false],
            ['name' => 'employees.create', 'display_name' => 'Crear Empleados', 'module' => 'employees', 'action' => 'create', 'is_critical' => false],
            ['name' => 'employees.edit', 'display_name' => 'Editar Empleados', 'module' => 'employees', 'action' => 'edit', 'is_critical' => false],
            ['name' => 'employees.delete', 'display_name' => 'Eliminar Empleados', 'module' => 'employees', 'action' => 'delete', 'is_critical' => true],
            ['name' => 'employees.export', 'display_name' => 'Exportar Empleados', 'module' => 'employees', 'action' => 'export', 'is_critical' => false],

            // Kardex
            ['name' => 'kardex.view', 'display_name' => 'Ver Kardex', 'module' => 'kardex', 'action' => 'view', 'is_critical' => false],
            ['name' => 'kardex.view_sensitive', 'display_name' => 'Ver Kardex Sensible', 'module' => 'kardex', 'action' => 'view', 'is_critical' => true],
            ['name' => 'kardex.edit', 'display_name' => 'Editar Kardex', 'module' => 'kardex', 'action' => 'edit', 'is_critical' => false],
            ['name' => 'kardex.edit_sensitive', 'display_name' => 'Editar Kardex Sensible', 'module' => 'kardex', 'action' => 'edit', 'is_critical' => true],

            // Seniority
            ['name' => 'employee_seniority_hours.view', 'display_name' => 'Ver Antigüedad Histórica', 'module' => 'employee_seniority_hours', 'action' => 'view', 'is_critical' => false],
            ['name' => 'employee_seniority_hours.edit', 'display_name' => 'Editar Antigüedad', 'module' => 'employee_seniority_hours', 'action' => 'edit', 'is_critical' => false],
            ['name' => 'employee_seniority_hours.edit_validated', 'display_name' => 'Editar Antigüedad Validada', 'module' => 'employee_seniority_hours', 'action' => 'edit', 'is_critical' => true],
            ['name' => 'employee_seniority_hours.validate', 'display_name' => 'Validar Antigüedad', 'module' => 'employee_seniority_hours', 'action' => 'validate', 'is_critical' => false],
            ['name' => 'employee_seniority_hours.reopen_validated', 'display_name' => 'Reabrir Antigüedad Validada', 'module' => 'employee_seniority_hours', 'action' => 'reopen', 'is_critical' => true],

            // Historical Benefits
            ['name' => 'historical_benefits.view', 'display_name' => 'Ver Prestaciones Históricas', 'module' => 'historical_benefits', 'action' => 'view', 'is_critical' => false],
            ['name' => 'historical_benefits.edit', 'display_name' => 'Editar Prestaciones Históricas', 'module' => 'historical_benefits', 'action' => 'edit', 'is_critical' => false],
            ['name' => 'historical_benefits.edit_validated', 'display_name' => 'Editar Prestaciones Validadas', 'module' => 'historical_benefits', 'action' => 'edit', 'is_critical' => true],
            ['name' => 'historical_benefits.validate', 'display_name' => 'Validar Prestaciones', 'module' => 'historical_benefits', 'action' => 'validate', 'is_critical' => false],

            // Vacation
            ['name' => 'vacation_balances.view', 'display_name' => 'Ver Vacaciones', 'module' => 'vacation_balances', 'action' => 'view', 'is_critical' => false],
            ['name' => 'vacation_balances.manual_adjust', 'display_name' => 'Ajustar Vacaciones Manualmente', 'module' => 'vacation_balances', 'action' => 'edit', 'is_critical' => true],
            ['name' => 'vacation_requests.view', 'display_name' => 'Ver Solicitudes de Vacaciones', 'module' => 'vacation_requests', 'action' => 'view', 'is_critical' => false],
            ['name' => 'vacation_requests.create', 'display_name' => 'Solicitar Vacaciones', 'module' => 'vacation_requests', 'action' => 'create', 'is_critical' => false],
            ['name' => 'vacation_requests.approve', 'display_name' => 'Aprobar Vacaciones', 'module' => 'vacation_requests', 'action' => 'approve', 'is_critical' => false],
            ['name' => 'vacation_requests.reject', 'display_name' => 'Rechazar Vacaciones', 'module' => 'vacation_requests', 'action' => 'reject', 'is_critical' => false],

            // Attendance
            ['name' => 'attendance.view', 'display_name' => 'Ver Asistencia', 'module' => 'attendance', 'action' => 'view', 'is_critical' => false],
            ['name' => 'attendance.register', 'display_name' => 'Registrar Asistencia', 'module' => 'attendance', 'action' => 'create', 'is_critical' => false],
            ['name' => 'attendance.edit', 'display_name' => 'Editar Asistencia', 'module' => 'attendance', 'action' => 'edit', 'is_critical' => false],
            ['name' => 'attendance.export', 'display_name' => 'Exportar Asistencia', 'module' => 'attendance', 'action' => 'export', 'is_critical' => false],

            // Delays
            ['name' => 'delays.view', 'display_name' => 'Ver Retardos', 'module' => 'delays', 'action' => 'view', 'is_critical' => false],
            ['name' => 'delays.edit', 'display_name' => 'Editar Retardos', 'module' => 'delays', 'action' => 'edit', 'is_critical' => false],

            // Absences
            ['name' => 'absences.view', 'display_name' => 'Ver Faltas', 'module' => 'absences', 'action' => 'view', 'is_critical' => false],
            ['name' => 'absences.edit', 'display_name' => 'Editar Faltas', 'module' => 'absences', 'action' => 'edit', 'is_critical' => false],

            // Account Suspensions
            ['name' => 'account_suspensions.view', 'display_name' => 'Ver Suspensiones', 'module' => 'account_suspensions', 'action' => 'view', 'is_critical' => false],
            ['name' => 'account_suspensions.create', 'display_name' => 'Crear Suspensiones', 'module' => 'account_suspensions', 'action' => 'create', 'is_critical' => false],
            ['name' => 'account_suspensions.reactivate', 'display_name' => 'Reactivar Cuentas', 'module' => 'account_suspensions', 'action' => 'reactivate', 'is_critical' => true],

            // Administrative Warnings
            ['name' => 'administrative_warnings.view', 'display_name' => 'Ver Llamadas de Atención', 'module' => 'administrative_warnings', 'action' => 'view', 'is_critical' => false],
            ['name' => 'administrative_warnings.create', 'display_name' => 'Crear Llamadas de Atención', 'module' => 'administrative_warnings', 'action' => 'create', 'is_critical' => false],
            ['name' => 'administrative_warnings.edit', 'display_name' => 'Editar Llamadas de Atención', 'module' => 'administrative_warnings', 'action' => 'edit', 'is_critical' => false],

            // Tasks
            ['name' => 'tasks.view', 'display_name' => 'Ver Tareas', 'module' => 'tasks', 'action' => 'view', 'is_critical' => false],
            ['name' => 'tasks.create', 'display_name' => 'Crear Tareas', 'module' => 'tasks', 'action' => 'create', 'is_critical' => false],
            ['name' => 'tasks.edit', 'display_name' => 'Editar Tareas', 'module' => 'tasks', 'action' => 'edit', 'is_critical' => false],
            ['name' => 'tasks.delete', 'display_name' => 'Eliminar Tareas', 'module' => 'tasks', 'action' => 'delete', 'is_critical' => false],
            ['name' => 'tasks.assign', 'display_name' => 'Asignar Tareas', 'module' => 'tasks', 'action' => 'assign', 'is_critical' => false],
            ['name' => 'task_evidences.review', 'display_name' => 'Revisar Evidencias', 'module' => 'task_evidences', 'action' => 'review', 'is_critical' => false],

            // Routines
            ['name' => 'routines.view', 'display_name' => 'Ver Rutinas', 'module' => 'routines', 'action' => 'view', 'is_critical' => false],
            ['name' => 'routines.create', 'display_name' => 'Crear Rutinas', 'module' => 'routines', 'action' => 'create', 'is_critical' => false],
            ['name' => 'routines.edit', 'display_name' => 'Editar Rutinas', 'module' => 'routines', 'action' => 'edit', 'is_critical' => false],
            ['name' => 'routines.assign', 'display_name' => 'Asignar Rutinas', 'module' => 'routines', 'action' => 'assign', 'is_critical' => false],

            // Live Operation
            ['name' => 'live_operation.view', 'display_name' => 'Ver Operación en Vivo', 'module' => 'live_operation', 'action' => 'view', 'is_critical' => false],

            // Reports
            ['name' => 'reports.view', 'display_name' => 'Ver Reportes', 'module' => 'reports', 'action' => 'view', 'is_critical' => false],
            ['name' => 'reports.export', 'display_name' => 'Exportar Reportes', 'module' => 'reports', 'action' => 'export', 'is_critical' => false],
            ['name' => 'reports.export_sensitive', 'display_name' => 'Exportar Reportes Sensibles', 'module' => 'reports', 'action' => 'export', 'is_critical' => true],

            // Payroll
            ['name' => 'payroll.view', 'display_name' => 'Ver Nómina', 'module' => 'payroll', 'action' => 'view', 'is_critical' => false],
            ['name' => 'payroll.view_all_salaries', 'display_name' => 'Ver Todos los Sueldos', 'module' => 'payroll', 'action' => 'view', 'is_critical' => true],
            ['name' => 'payroll.close', 'display_name' => 'Cerrar Nómina', 'module' => 'payroll', 'action' => 'close', 'is_critical' => true],
            ['name' => 'payroll.reopen', 'display_name' => 'Reabrir Nómina', 'module' => 'payroll', 'action' => 'reopen', 'is_critical' => true],

            // Contracts
            ['name' => 'contracts.view', 'display_name' => 'Ver Contratos', 'module' => 'contracts', 'action' => 'view', 'is_critical' => false],
            ['name' => 'contracts.create', 'display_name' => 'Crear Contratos', 'module' => 'contracts', 'action' => 'create', 'is_critical' => false],
            ['name' => 'contracts.edit', 'display_name' => 'Editar Contratos', 'module' => 'contracts', 'action' => 'edit', 'is_critical' => true],
            ['name' => 'contracts.sign', 'display_name' => 'Firmar Contratos', 'module' => 'contracts', 'action' => 'sign', 'is_critical' => false],

            // Legal Rules
            ['name' => 'legal_rules.view', 'display_name' => 'Ver Reglas Laborales', 'module' => 'legal_rules', 'action' => 'view', 'is_critical' => false],
            ['name' => 'legal_rules.configure', 'display_name' => 'Configurar Reglas Laborales', 'module' => 'legal_rules', 'action' => 'configure', 'is_critical' => true],

            // Settings
            ['name' => 'settings.view', 'display_name' => 'Ver Configuración', 'module' => 'settings', 'action' => 'view', 'is_critical' => false],
            ['name' => 'settings.configure', 'display_name' => 'Configurar Sistema', 'module' => 'settings', 'action' => 'configure', 'is_critical' => true],

            // Audit
            ['name' => 'audit_logs.view', 'display_name' => 'Ver Auditoría', 'module' => 'audit_logs', 'action' => 'view', 'is_critical' => false],
            ['name' => 'audit_logs.delete', 'display_name' => 'Eliminar Auditoría', 'module' => 'audit_logs', 'action' => 'delete', 'is_critical' => true],

            // Candidates
            ['name' => 'candidates.view', 'display_name' => 'Ver Candidatos', 'module' => 'candidates', 'action' => 'view', 'is_critical' => false],
            ['name' => 'candidates.create', 'display_name' => 'Crear Candidatos', 'module' => 'candidates', 'action' => 'create', 'is_critical' => false],
            ['name' => 'candidates.edit', 'display_name' => 'Editar Candidatos', 'module' => 'candidates', 'action' => 'edit', 'is_critical' => false],

            // Vacancies
            ['name' => 'vacancies.view', 'display_name' => 'Ver Vacantes', 'module' => 'vacancies', 'action' => 'view', 'is_critical' => false],
            ['name' => 'vacancies.create', 'display_name' => 'Crear Vacantes', 'module' => 'vacancies', 'action' => 'create', 'is_critical' => false],
            ['name' => 'vacancies.edit', 'display_name' => 'Editar Vacantes', 'module' => 'vacancies', 'action' => 'edit', 'is_critical' => false],

            // Org Chart
            ['name' => 'org_chart.view', 'display_name' => 'Ver Organigrama', 'module' => 'org_chart', 'action' => 'view', 'is_critical' => false],
            ['name' => 'org_chart.edit', 'display_name' => 'Editar Organigrama', 'module' => 'org_chart', 'action' => 'edit', 'is_critical' => false],

            // Courses
            ['name' => 'courses.view', 'display_name' => 'Ver Cursos', 'module' => 'courses', 'action' => 'view', 'is_critical' => false],
            ['name' => 'courses.create', 'display_name' => 'Crear Cursos', 'module' => 'courses', 'action' => 'create', 'is_critical' => false],
            ['name' => 'courses.assign', 'display_name' => 'Asignar Cursos', 'module' => 'courses', 'action' => 'assign', 'is_critical' => false],

            // Internal Promotions
            ['name' => 'internal_promotions.view', 'display_name' => 'Ver Promociones', 'module' => 'internal_promotions', 'action' => 'view', 'is_critical' => false],
            ['name' => 'internal_promotions.approve', 'display_name' => 'Aprobar Promociones', 'module' => 'internal_promotions', 'action' => 'approve', 'is_critical' => false],

            // Benefits
            ['name' => 'benefits.view', 'display_name' => 'Ver Prestaciones', 'module' => 'benefits', 'action' => 'view', 'is_critical' => false],
            ['name' => 'benefits.configure', 'display_name' => 'Configurar Prestaciones', 'module' => 'benefits', 'action' => 'configure', 'is_critical' => true],

            // Christmas Bonus
            ['name' => 'christmas_bonus.view', 'display_name' => 'Ver Aguinaldo', 'module' => 'christmas_bonus', 'action' => 'view', 'is_critical' => false],
            ['name' => 'christmas_bonus.manual_adjust', 'display_name' => 'Ajustar Aguinaldo', 'module' => 'christmas_bonus', 'action' => 'edit', 'is_critical' => true],

            // Profit Sharing
            ['name' => 'profit_sharing.view', 'display_name' => 'Ver PTU', 'module' => 'profit_sharing', 'action' => 'view', 'is_critical' => false],
            ['name' => 'profit_sharing.approve', 'display_name' => 'Aprobar PTU', 'module' => 'profit_sharing', 'action' => 'approve', 'is_critical' => true],
            ['name' => 'profit_sharing.manual_adjust', 'display_name' => 'Ajustar PTU', 'module' => 'profit_sharing', 'action' => 'edit', 'is_critical' => true],

            // Documents
            ['name' => 'documents.view', 'display_name' => 'Ver Documentos', 'module' => 'documents', 'action' => 'view', 'is_critical' => false],
            ['name' => 'documents.upload', 'display_name' => 'Subir Documentos', 'module' => 'documents', 'action' => 'upload', 'is_critical' => false],
            ['name' => 'documents.download', 'display_name' => 'Descargar Documentos', 'module' => 'documents', 'action' => 'download', 'is_critical' => false],

            // Terminations
            ['name' => 'terminations.view', 'display_name' => 'Ver Bajas', 'module' => 'terminations', 'action' => 'view', 'is_critical' => false],
            ['name' => 'terminations.approve', 'display_name' => 'Aprobar Bajas', 'module' => 'terminations', 'action' => 'approve', 'is_critical' => true],

            // Severance
            ['name' => 'severance.view', 'display_name' => 'Ver Finiquitos', 'module' => 'severance', 'action' => 'view', 'is_critical' => false],
            ['name' => 'severance.approve', 'display_name' => 'Aprobar Finiquitos', 'module' => 'severance', 'action' => 'approve', 'is_critical' => true],

            // Holidays
            ['name' => 'holidays.view', 'display_name' => 'Ver Días Feriados', 'module' => 'holidays', 'action' => 'view', 'is_critical' => false],
            ['name' => 'holidays.create', 'display_name' => 'Crear Días Feriados', 'module' => 'holidays', 'action' => 'create', 'is_critical' => false],
            ['name' => 'holidays.edit', 'display_name' => 'Editar Días Feriados', 'module' => 'holidays', 'action' => 'edit', 'is_critical' => false],

            // Overtime
            ['name' => 'overtime.view', 'display_name' => 'Ver Horas Extra', 'module' => 'overtime', 'action' => 'view', 'is_critical' => false],
            ['name' => 'overtime.request', 'display_name' => 'Solicitar Horas Extra', 'module' => 'overtime', 'action' => 'create', 'is_critical' => false],
            ['name' => 'overtime.approve', 'display_name' => 'Aprobar Horas Extra', 'module' => 'overtime', 'action' => 'approve', 'is_critical' => false],

            // Backups
            ['name' => 'backups.view', 'display_name' => 'Ver Backups', 'module' => 'backups', 'action' => 'view', 'is_critical' => false],
            ['name' => 'backups.restore', 'display_name' => 'Restaurar Backups', 'module' => 'backups', 'action' => 'restore', 'is_critical' => true],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
