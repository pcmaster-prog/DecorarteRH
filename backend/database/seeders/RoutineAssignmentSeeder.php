<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoutineAssignment;
use App\Models\RoutineProgress;

class RoutineAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        // Juan Pérez - Rutina de apertura
        $ra1 = RoutineAssignment::create([
            'routine_id' => 1,
            'employee_id' => 6,
            'assigned_by' => 5,
            'assigned_date' => now(),
            'started_at' => now()->setTime(8, 30),
            'completed_at' => now()->setTime(9, 0),
            'status' => 'completed',
            'progress_percentage' => 100,
        ]);

        RoutineProgress::create([
            'routine_assignment_id' => $ra1->id,
            'routine_task_id' => 1,
            'task_assignment_id' => 1,
            'status' => 'completed',
            'started_at' => now()->setTime(8, 30),
            'completed_at' => now()->setTime(9, 0),
        ]);

        // María González - Rutina de revisión de caducidades
        $ra2 = RoutineAssignment::create([
            'routine_id' => 6,
            'employee_id' => 7,
            'assigned_by' => 5,
            'assigned_date' => now(),
            'started_at' => now()->setTime(8, 35),
            'completed_at' => now()->setTime(9, 45),
            'status' => 'completed',
            'progress_percentage' => 100,
        ]);

        RoutineProgress::create([
            'routine_assignment_id' => $ra2->id,
            'routine_task_id' => 9,
            'task_assignment_id' => 4,
            'status' => 'completed',
            'started_at' => now()->setTime(8, 35),
            'completed_at' => now()->setTime(9, 45),
        ]);

        // Sofía Torres - Rutina de cierre (pendiente)
        RoutineAssignment::create([
            'routine_id' => 2,
            'employee_id' => 9,
            'assigned_by' => 3,
            'assigned_date' => now(),
            'started_at' => null,
            'completed_at' => null,
            'status' => 'pending',
            'progress_percentage' => 0,
        ]);
    }
}
