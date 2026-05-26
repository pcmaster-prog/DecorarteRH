<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusinessSeason;

class BusinessSeasonSeeder extends Seeder
{
    public function run(): void
    {
        $seasons = [
            [
                'name' => 'temporada_alta_navidad',
                'display_name' => 'Temporada Alta Navidad',
                'type' => 'high',
                'start_date' => '2024-11-01',
                'end_date' => '2024-12-31',
                'color' => '#E74C3C',
                'is_vacation_blocked' => false,
                'requires_special_approval' => true,
                'max_vacation_employees_per_day' => 1,
                'max_vacation_employees_per_area' => 0,
                'notes' => 'Temporada alta de ventas navideñas. Ley Silla activa. Vacaciones requieren autorización especial.',
                'is_active' => true,
            ],
            [
                'name' => 'temporada_baja_febrero',
                'display_name' => 'Temporada Baja - Febrero',
                'type' => 'low',
                'start_date' => '2024-02-01',
                'end_date' => '2024-02-29',
                'color' => '#27AE60',
                'is_vacation_blocked' => false,
                'requires_special_approval' => false,
                'max_vacation_employees_per_day' => 3,
                'max_vacation_employees_per_area' => 1,
                'notes' => 'Temporada baja. Vacaciones permitidas según cobertura.',
                'is_active' => true,
            ],
            [
                'name' => 'temporada_baja_marzo',
                'display_name' => 'Temporada Baja - Marzo',
                'type' => 'low',
                'start_date' => '2024-03-01',
                'end_date' => '2024-03-31',
                'color' => '#27AE60',
                'is_vacation_blocked' => false,
                'requires_special_approval' => false,
                'max_vacation_employees_per_day' => 3,
                'max_vacation_employees_per_area' => 1,
                'notes' => 'Temporada baja. Vacaciones permitidas según cobertura.',
                'is_active' => true,
            ],
            [
                'name' => 'temporada_baja_junio',
                'display_name' => 'Temporada Baja - Junio',
                'type' => 'low',
                'start_date' => '2024-06-01',
                'end_date' => '2024-06-30',
                'color' => '#27AE60',
                'is_vacation_blocked' => false,
                'requires_special_approval' => false,
                'max_vacation_employees_per_day' => 3,
                'max_vacation_employees_per_area' => 1,
                'notes' => 'Temporada baja. Vacaciones permitidas según cobertura.',
                'is_active' => true,
            ],
            [
                'name' => 'temporada_baja_septiembre',
                'display_name' => 'Temporada Baja - Septiembre',
                'type' => 'low',
                'start_date' => '2024-09-01',
                'end_date' => '2024-09-30',
                'color' => '#27AE60',
                'is_vacation_blocked' => false,
                'requires_special_approval' => false,
                'max_vacation_employees_per_day' => 3,
                'max_vacation_employees_per_area' => 1,
                'notes' => 'Temporada baja. Vacaciones permitidas según cobertura.',
                'is_active' => true,
            ],
            [
                'name' => 'temporada_regular',
                'display_name' => 'Temporada Regular',
                'type' => 'regular',
                'start_date' => '2024-04-01',
                'end_date' => '2024-05-31',
                'color' => '#3498DB',
                'is_vacation_blocked' => false,
                'requires_special_approval' => false,
                'max_vacation_employees_per_day' => 2,
                'max_vacation_employees_per_area' => 1,
                'notes' => 'Temporada regular. Vacaciones según disponibilidad.',
                'is_active' => true,
            ],
        ];

        foreach ($seasons as $season) {
            BusinessSeason::create($season);
        }
    }
}
