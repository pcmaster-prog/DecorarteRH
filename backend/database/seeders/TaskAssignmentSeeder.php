<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskAssignment;

class TaskAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = [
            // Juan Pérez - Tareas de hoy
            [
                'task_id' => 1,
                'employee_id' => 6,
                'assigned_by' => 5,
                'assigned_at' => now()->setTime(8, 0),
                'due_date' => now(),
                'due_time' => now()->setTime(10, 0),
                'started_at' => now()->setTime(8, 45),
                'completed_at' => now()->setTime(9, 30),
                'status' => 'completed',
                'notes' => 'Anaquel limpio y organizado.',
            ],
            [
                'task_id' => 12,
                'employee_id' => 6,
                'assigned_by' => 5,
                'assigned_at' => now()->setTime(8, 0),
                'due_date' => now(),
                'due_time' => now()->setTime(11, 0),
                'started_at' => now()->setTime(9, 45),
                'completed_at' => now()->setTime(10, 30),
                'status' => 'completed',
                'notes' => 'Evidencia subida correctamente.',
            ],
            [
                'task_id' => 3,
                'employee_id' => 6,
                'assigned_by' => 5,
                'assigned_at' => now()->setTime(8, 0),
                'due_date' => now(),
                'due_time' => now()->setTime(16, 0),
                'started_at' => null,
                'completed_at' => null,
                'status' => 'pending',
                'notes' => 'Pendiente de realizar.',
            ],
            // María González - Tareas de hoy
            [
                'task_id' => 2,
                'employee_id' => 7,
                'assigned_by' => 5,
                'assigned_at' => now()->setTime(8, 0),
                'due_date' => now(),
                'due_time' => now()->setTime(12, 0),
                'started_at' => now()->setTime(8, 35),
                'completed_at' => now()->setTime(9, 45),
                'status' => 'completed',
                'notes' => 'Caducidades revisadas. Sin productos vencidos.',
            ],
            [
                'task_id' => 10,
                'employee_id' => 7,
                'assigned_by' => 5,
                'assigned_at' => now()->setTime(8, 0),
                'due_date' => now(),
                'due_time' => now()->setTime(15, 0),
                'started_at' => null,
                'completed_at' => null,
                'status' => 'pending',
                'notes' => 'Pendiente de realizar.',
            ],
            // Sofía Torres - Tareas de hoy
            [
                'task_id' => 5,
                'employee_id' => 9,
                'assigned_by' => 3,
                'assigned_at' => now()->setTime(8, 0),
                'due_date' => now(),
                'due_time' => now()->setTime(14, 0),
                'started_at' => now()->setTime(8, 30),
                'completed_at' => null,
                'status' => 'in_progress',
                'notes' => 'Apoyando en caja por alta demanda.',
            ],
            // Diego Morales - Tareas de hoy
            [
                'task_id' => 7,
                'employee_id' => 10,
                'assigned_by' => 5,
                'assigned_at' => now()->setTime(10, 0),
                'due_date' => now(),
                'due_time' => now()->setTime(12, 0),
                'started_at' => now()->setTime(10, 15),
                'completed_at' => null,
                'status' => 'in_progress',
                'notes' => 'Buscando productos en almacén.',
            ],
        ];

        foreach ($assignments as $assignment) {
            TaskAssignment::create($assignment);
        }
    }
}
