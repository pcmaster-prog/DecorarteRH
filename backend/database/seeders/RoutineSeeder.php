<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Routine;
use App\Models\RoutineTask;

class RoutineSeeder extends Seeder
{
    public function run(): void
    {
        $routines = [
            [
                'name' => 'rutina_apertura',
                'display_name' => 'Rutina de Apertura',
                'description' => 'Tareas diarias de apertura de tienda',
                'category' => 'opening',
                'frequency' => 'daily',
                'department_id' => null,
                'position_id' => null,
                'estimated_duration_minutes' => 30,
                'created_by' => 1,
            ],
            [
                'name' => 'rutina_cierre',
                'display_name' => 'Rutina de Cierre',
                'description' => 'Tareas diarias de cierre de tienda',
                'category' => 'closing',
                'frequency' => 'daily',
                'department_id' => null,
                'position_id' => null,
                'estimated_duration_minutes' => 45,
                'created_by' => 1,
            ],
            [
                'name' => 'rutina_lunes_chocolates',
                'display_name' => 'Rutina Lunes - Área Chocolates',
                'description' => 'Rutina semanal de revisión y limpieza del área de chocolates',
                'category' => 'area',
                'frequency' => 'weekly',
                'day_of_week' => 1,
                'department_id' => 5,
                'position_id' => 4,
                'estimated_duration_minutes' => 60,
                'created_by' => 1,
            ],
            [
                'name' => 'rutina_limpieza_mostrador',
                'display_name' => 'Rutina de Limpieza de Mostrador',
                'description' => 'Limpieza profunda de mostradores de atención',
                'category' => 'cleaning',
                'frequency' => 'daily',
                'department_id' => 5,
                'position_id' => 4,
                'estimated_duration_minutes' => 20,
                'created_by' => 1,
            ],
            [
                'name' => 'rutina_recepcion_mercancia',
                'display_name' => 'Rutina de Recepción de Mercancía',
                'description' => 'Procedimiento de recepción y almacenamiento de mercancía',
                'category' => 'inventory',
                'frequency' => 'daily',
                'department_id' => 4,
                'position_id' => 6,
                'estimated_duration_minutes' => 90,
                'created_by' => 1,
            ],
            [
                'name' => 'rutina_revision_caducidades',
                'display_name' => 'Rutina de Revisión de Caducidades',
                'description' => 'Revisión diaria de fechas de caducidad en toda la tienda',
                'category' => 'inventory',
                'frequency' => 'daily',
                'department_id' => null,
                'position_id' => 4,
                'estimated_duration_minutes' => 40,
                'created_by' => 1,
            ],
        ];

        foreach ($routines as $routine) {
            Routine::create($routine);
        }

        // Asignar tareas a rutinas
        $routineTasks = [
            // Rutina de Apertura (ID 1)
            ['routine_id' => 1, 'task_id' => 5, 'order' => 1, 'is_required' => true], // Apoyar en caja
            ['routine_id' => 1, 'task_id' => 9, 'order' => 2, 'is_required' => true], // Limpiar mostrador

            // Rutina de Cierre (ID 2)
            ['routine_id' => 2, 'task_id' => 1, 'order' => 1, 'is_required' => true], // Limpiar anaquel chocolates
            ['routine_id' => 2, 'task_id' => 11, 'order' => 2, 'is_required' => true], // Reportar faltantes

            // Rutina Lunes Chocolates (ID 3)
            ['routine_id' => 3, 'task_id' => 1, 'order' => 1, 'is_required' => true], // Limpiar anaquel
            ['routine_id' => 3, 'task_id' => 10, 'order' => 2, 'is_required' => true], // Revisar etiquetas
            ['routine_id' => 3, 'task_id' => 3, 'order' => 3, 'is_required' => true], // Reponer capacillos
            ['routine_id' => 3, 'task_id' => 11, 'order' => 4, 'is_required' => true], // Reportar faltantes
            ['routine_id' => 3, 'task_id' => 12, 'order' => 5, 'is_required' => true], // Subir evidencia

            // Rutina Limpieza Mostrador (ID 4)
            ['routine_id' => 4, 'task_id' => 9, 'order' => 1, 'is_required' => true], // Limpiar mostrador
            ['routine_id' => 4, 'task_id' => 10, 'order' => 2, 'is_required' => false], // Revisar etiquetas

            // Rutina Recepción Mercancía (ID 5)
            ['routine_id' => 5, 'task_id' => 8, 'order' => 1, 'is_required' => true], // Apoyar recepción
            ['routine_id' => 5, 'task_id' => 7, 'order' => 2, 'is_required' => true], // Buscar producto

            // Rutina Revisión Caducidades (ID 6)
            ['routine_id' => 6, 'task_id' => 2, 'order' => 1, 'is_required' => true], // Revisar caducidades harinas
            ['routine_id' => 6, 'task_id' => 11, 'order' => 2, 'is_required' => true], // Reportar faltantes
        ];

        foreach ($routineTasks as $rt) {
            RoutineTask::create($rt);
        }
    }
}
