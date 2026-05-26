<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            [
                'name' => 'jornada_base',
                'display_name' => 'Jornada Base',
                'entry_time' => '08:30:00',
                'exit_time' => '17:00:00',
                'meal_duration_minutes' => 30,
                'tolerance_minutes' => 10,
                'days_per_week' => 6,
                'is_night_shift' => false,
                'is_active' => true,
                'notes' => 'Jornada base de DecorArte: 8:30 a.m. a 5:00 p.m. con 30 minutos de comida.',
            ],
            [
                'name' => 'jornada_madrugada',
                'display_name' => 'Jornada Madrugada',
                'entry_time' => '06:00:00',
                'exit_time' => '14:30:00',
                'meal_duration_minutes' => 30,
                'tolerance_minutes' => 10,
                'days_per_week' => 6,
                'is_night_shift' => false,
                'is_active' => true,
                'notes' => 'Jornada de madrugada para producción.',
            ],
            [
                'name' => 'jornada_tarde',
                'display_name' => 'Jornada Tarde',
                'entry_time' => '14:00:00',
                'exit_time' => '22:30:00',
                'meal_duration_minutes' => 30,
                'tolerance_minutes' => 10,
                'days_per_week' => 6,
                'is_night_shift' => true,
                'is_active' => true,
                'notes' => 'Jornada de tarde para cobertura extendida.',
            ],
            [
                'name' => 'media_jornada',
                'display_name' => 'Media Jornada',
                'entry_time' => '08:30:00',
                'exit_time' => '14:30:00',
                'meal_duration_minutes' => 30,
                'tolerance_minutes' => 10,
                'days_per_week' => 6,
                'is_night_shift' => false,
                'is_active' => true,
                'notes' => 'Media jornada de 6 horas efectivas.',
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}
