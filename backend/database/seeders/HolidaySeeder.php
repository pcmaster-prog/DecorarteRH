<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Holiday;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            ['name' => 'Año Nuevo', 'date' => '2024-01-01', 'type' => 'fixed', 'is_recurring' => true, 'year' => null, 'is_paid' => true],
            ['name' => 'Día de la Constitución', 'date' => '2024-02-05', 'type' => 'movable', 'is_recurring' => true, 'year' => null, 'is_paid' => true],
            ['name' => 'Natalicio de Benito Juárez', 'date' => '2024-03-18', 'type' => 'movable', 'is_recurring' => true, 'year' => null, 'is_paid' => true],
            ['name' => 'Día del Trabajo', 'date' => '2024-05-01', 'type' => 'fixed', 'is_recurring' => true, 'year' => null, 'is_paid' => true],
            ['name' => 'Día de la Independencia', 'date' => '2024-09-16', 'type' => 'fixed', 'is_recurring' => true, 'year' => null, 'is_paid' => true],
            ['name' => 'Día de la Revolución', 'date' => '2024-11-18', 'type' => 'movable', 'is_recurring' => true, 'year' => null, 'is_paid' => true],
            ['name' => 'Transmisión del Poder Ejecutivo', 'date' => '2024-10-01', 'type' => 'special', 'is_recurring' => false, 'year' => 2024, 'is_paid' => true],
            ['name' => 'Navidad', 'date' => '2024-12-25', 'type' => 'fixed', 'is_recurring' => true, 'year' => null, 'is_paid' => true],
        ];

        foreach ($holidays as $holiday) {
            Holiday::create($holiday);
        }
    }
}
