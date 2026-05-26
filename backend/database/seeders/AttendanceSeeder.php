<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceLog;
use App\Models\DelayRecord;
use App\Models\AbsenceRecord;
use App\Models\MealBreak;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // Juan Pérez - Asistencia de hoy (completo con 1 retardo)
        AttendanceLog::create([
            'employee_id' => 6,
            'date' => now(),
            'entry_time' => now()->setTime(8, 40),
            'meal_start_time' => now()->setTime(13, 30),
            'meal_end_time' => now()->setTime(14, 0),
            'exit_time' => now()->setTime(17, 10),
            'expected_entry' => now()->setTime(8, 30),
            'expected_exit' => now()->setTime(17, 0),
            'tolerance_minutes' => 10,
            'is_delay' => true,
            'delay_minutes' => 10,
            'is_absence' => false,
            'worked_hours' => 8.5,
            'effective_hours' => 8.0,
            'notes' => 'Entrada con 10 minutos de retardo. Salida ajustada a 5:10 p.m.',
        ]);

        DelayRecord::create([
            'employee_id' => 6,
            'attendance_log_id' => 1,
            'date' => now(),
            'expected_time' => now()->setTime(8, 30),
            'actual_time' => now()->setTime(8, 40),
            'delay_minutes' => 10,
            'tolerance_minutes' => 10,
            'is_converted_to_absence' => false,
            'notes' => 'Primer retardo acumulado.',
        ]);

        // María González - Asistencia de hoy (perfecta)
        AttendanceLog::create([
            'employee_id' => 7,
            'date' => now(),
            'entry_time' => now()->setTime(8, 28),
            'meal_start_time' => now()->setTime(13, 30),
            'meal_end_time' => now()->setTime(14, 0),
            'exit_time' => now()->setTime(17, 0),
            'expected_entry' => now()->setTime(8, 30),
            'expected_exit' => now()->setTime(17, 0),
            'tolerance_minutes' => 10,
            'is_delay' => false,
            'delay_minutes' => 0,
            'is_absence' => false,
            'worked_hours' => 8.5,
            'effective_hours' => 8.0,
            'notes' => 'Asistencia perfecta.',
        ]);

        // Pedro Ramírez - Faltas acumuladas (3 faltas = suspensión)
        // Falta 1
        AttendanceLog::create([
            'employee_id' => 8,
            'date' => now()->subDays(5),
            'entry_time' => null,
            'expected_entry' => now()->subDays(5)->setTime(8, 30),
            'expected_exit' => now()->subDays(5)->setTime(17, 0),
            'is_delay' => false,
            'is_absence' => true,
            'absence_type' => 'unjustified',
            'worked_hours' => 0,
            'effective_hours' => 0,
            'notes' => 'Falta injustificada.',
        ]);

        AbsenceRecord::create([
            'employee_id' => 8,
            'date' => now()->subDays(5),
            'type' => 'unjustified',
            'reason' => 'No se presentó a laborar.',
            'is_justified' => false,
            'registered_by' => 1,
        ]);

        // Falta 2
        AttendanceLog::create([
            'employee_id' => 8,
            'date' => now()->subDays(3),
            'entry_time' => null,
            'expected_entry' => now()->subDays(3)->setTime(8, 30),
            'expected_exit' => now()->subDays(3)->setTime(17, 0),
            'is_delay' => false,
            'is_absence' => true,
            'absence_type' => 'unjustified',
            'worked_hours' => 0,
            'effective_hours' => 0,
            'notes' => 'Falta injustificada.',
        ]);

        AbsenceRecord::create([
            'employee_id' => 8,
            'date' => now()->subDays(3),
            'type' => 'unjustified',
            'reason' => 'No se presentó a laborar.',
            'is_justified' => false,
            'registered_by' => 1,
        ]);

        // Falta 3
        AttendanceLog::create([
            'employee_id' => 8,
            'date' => now()->subDays(1),
            'entry_time' => null,
            'expected_entry' => now()->subDays(1)->setTime(8, 30),
            'expected_exit' => now()->subDays(1)->setTime(17, 0),
            'is_delay' => false,
            'is_absence' => true,
            'absence_type' => 'unjustified',
            'worked_hours' => 0,
            'effective_hours' => 0,
            'notes' => 'Falta injustificada. Tercera falta acumulada.',
        ]);

        AbsenceRecord::create([
            'employee_id' => 8,
            'date' => now()->subDays(1),
            'type' => 'unjustified',
            'reason' => 'No se presentó a laborar. Tercera falta acumulada.',
            'is_justified' => false,
            'registered_by' => 1,
        ]);

        // Sofía Torres - Asistencia de hoy
        AttendanceLog::create([
            'employee_id' => 9,
            'date' => now(),
            'entry_time' => now()->setTime(8, 25),
            'meal_start_time' => now()->setTime(13, 30),
            'meal_end_time' => now()->setTime(14, 0),
            'exit_time' => null,
            'expected_entry' => now()->setTime(8, 30),
            'expected_exit' => now()->setTime(17, 0),
            'tolerance_minutes' => 10,
            'is_delay' => false,
            'delay_minutes' => 0,
            'is_absence' => false,
            'worked_hours' => 4.5,
            'effective_hours' => 4.0,
            'notes' => 'En jornada. Trabajando en caja.',
        ]);

        // Diego Morales - Empleado por hora
        AttendanceLog::create([
            'employee_id' => 10,
            'date' => now(),
            'entry_time' => now()->setTime(10, 0),
            'meal_start_time' => now()->setTime(14, 0),
            'meal_end_time' => now()->setTime(15, 0),
            'exit_time' => null,
            'expected_entry' => now()->setTime(10, 0),
            'expected_exit' => now()->setTime(17, 0),
            'tolerance_minutes' => 10,
            'is_delay' => false,
            'delay_minutes' => 0,
            'is_absence' => false,
            'worked_hours' => 4.0,
            'effective_hours' => 4.0,
            'notes' => 'Empleado por hora. En jornada desde 10:00 a.m.',
        ]);

        // Comidas
        MealBreak::create([
            'employee_id' => 6,
            'attendance_log_id' => 1,
            'started_at' => now()->setTime(13, 30),
            'ended_at' => now()->setTime(14, 0),
            'expected_duration_minutes' => 30,
            'actual_duration_minutes' => 30,
            'is_exceeded' => false,
            'exceeded_minutes' => 0,
        ]);

        MealBreak::create([
            'employee_id' => 7,
            'attendance_log_id' => 2,
            'started_at' => now()->setTime(13, 30),
            'ended_at' => now()->setTime(14, 0),
            'expected_duration_minutes' => 30,
            'actual_duration_minutes' => 30,
            'is_exceeded' => false,
            'exceeded_minutes' => 0,
        ]);

        MealBreak::create([
            'employee_id' => 9,
            'attendance_log_id' => 6,
            'started_at' => now()->setTime(13, 30),
            'ended_at' => now()->setTime(14, 0),
            'expected_duration_minutes' => 30,
            'actual_duration_minutes' => 30,
            'is_exceeded' => false,
            'exceeded_minutes' => 0,
        ]);

        MealBreak::create([
            'employee_id' => 10,
            'attendance_log_id' => 7,
            'started_at' => now()->setTime(14, 0),
            'ended_at' => now()->setTime(15, 0),
            'expected_duration_minutes' => 30,
            'actual_duration_minutes' => 60,
            'is_exceeded' => true,
            'exceeded_minutes' => 30,
            'notes' => 'Comida excedida por 30 minutos.',
        ]);
    }
}
