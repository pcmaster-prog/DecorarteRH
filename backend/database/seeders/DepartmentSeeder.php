<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'direccion',
                'display_name' => 'Dirección',
                'description' => 'Dirección general de DecorArte',
                'color' => '#8B4513',
                'icon' => 'crown',
                'is_active' => true,
            ],
            [
                'name' => 'administracion',
                'display_name' => 'Administración',
                'description' => 'Administración general',
                'color' => '#2C3E50',
                'icon' => 'building',
                'is_active' => true,
            ],
            [
                'name' => 'cajas_compras',
                'display_name' => 'Cajas y Compras',
                'description' => 'Área de cajas y atención al cliente',
                'color' => '#27AE60',
                'icon' => 'cash-register',
                'is_active' => true,
            ],
            [
                'name' => 'produccion_almacen',
                'display_name' => 'Producción y Almacén',
                'description' => 'Área de producción y almacén',
                'color' => '#E67E22',
                'icon' => 'warehouse',
                'is_active' => true,
            ],
            [
                'name' => 'atencion_piso_venta',
                'display_name' => 'Atención y Piso de Venta',
                'description' => 'Área de atención al cliente y piso de venta',
                'color' => '#9B59B6',
                'icon' => 'users',
                'is_active' => true,
            ],
            [
                'name' => 'academia',
                'display_name' => 'Academia DecorArte',
                'description' => 'Área de capacitación y desarrollo',
                'color' => '#3498DB',
                'icon' => 'graduation-cap',
                'is_active' => true,
            ],
            [
                'name' => 'recursos_humanos',
                'display_name' => 'Recursos Humanos',
                'description' => 'Área de recursos humanos',
                'color' => '#E74C3C',
                'icon' => 'user-tie',
                'is_active' => true,
            ],
            [
                'name' => 'nomina',
                'display_name' => 'Nómina',
                'description' => 'Área de nómina y prestaciones',
                'color' => '#1ABC9C',
                'icon' => 'money-bill-wave',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
