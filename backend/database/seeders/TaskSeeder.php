<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            [
                'title' => 'Limpiar anaquel de chocolates',
                'description' => 'Limpiar y organizar el anaquel de productos de chocolate. Verificar etiquetas y precios.',
                'priority' => 'medium',
                'category' => 'cleaning',
                'estimated_minutes' => 30,
                'requires_evidence' => true,
                'evidence_type' => 'photo',
                'evidence_instructions' => 'Tomar foto del anaquel antes y después de limpiar.',
                'created_by' => 1,
            ],
            [
                'title' => 'Revisar caducidades en área de harinas',
                'description' => 'Revisar fechas de caducidad de todos los productos de harina y materias primas.',
                'priority' => 'high',
                'category' => 'inventory',
                'estimated_minutes' => 45,
                'requires_evidence' => true,
                'evidence_type' => 'photo',
                'evidence_instructions' => 'Tomar foto de productos próximos a caducar.',
                'created_by' => 1,
            ],
            [
                'title' => 'Reponer capacillos',
                'description' => 'Reponer capacillos en el área de repostería según inventario.',
                'priority' => 'medium',
                'category' => 'inventory',
                'estimated_minutes' => 20,
                'requires_evidence' => false,
                'created_by' => 1,
            ],
            [
                'title' => 'Tomar foto de evidencia del pasillo 3',
                'description' => 'Documentar el estado del pasillo 3 con fotografía.',
                'priority' => 'low',
                'category' => 'daily',
                'estimated_minutes' => 10,
                'requires_evidence' => true,
                'evidence_type' => 'photo',
                'evidence_instructions' => 'Tomar foto clara de todo el pasillo.',
                'created_by' => 1,
            ],
            [
                'title' => 'Apoyar en caja por alta demanda',
                'description' => 'Apoyar en operaciones de caja durante horas de alta demanda.',
                'priority' => 'critical',
                'category' => 'daily',
                'estimated_minutes' => 60,
                'requires_evidence' => false,
                'created_by' => 1,
            ],
            [
                'title' => 'Revisar precio de producto',
                'description' => 'Verificar que los precios en anaquel coincidan con el sistema.',
                'priority' => 'medium',
                'category' => 'daily',
                'estimated_minutes' => 15,
                'requires_evidence' => false,
                'created_by' => 1,
            ],
            [
                'title' => 'Buscar producto en almacén',
                'description' => 'Localizar producto solicitado por cliente en almacén.',
                'priority' => 'high',
                'category' => 'daily',
                'estimated_minutes' => 10,
                'requires_evidence' => false,
                'created_by' => 1,
            ],
            [
                'title' => 'Apoyar en recepción de mercancía',
                'description' => 'Ayudar en la recepción, revisión y almacenamiento de mercancía entrante.',
                'priority' => 'high',
                'category' => 'inventory',
                'estimated_minutes' => 90,
                'requires_evidence' => true,
                'evidence_type' => 'photo',
                'evidence_instructions' => 'Tomar foto de mercancía recibida y orden de compra.',
                'created_by' => 1,
            ],
            [
                'title' => 'Limpiar superficie de mostrador',
                'description' => 'Limpiar y desinfectar superficies de mostrador de atención.',
                'priority' => 'medium',
                'category' => 'cleaning',
                'estimated_minutes' => 15,
                'requires_evidence' => true,
                'evidence_type' => 'photo',
                'evidence_instructions' => 'Tomar foto del mostrador limpio.',
                'created_by' => 1,
            ],
            [
                'title' => 'Revisar etiquetas de precios',
                'description' => 'Verificar que todas las etiquetas de precios estén correctas y visibles.',
                'priority' => 'low',
                'category' => 'daily',
                'estimated_minutes' => 25,
                'requires_evidence' => false,
                'created_by' => 1,
            ],
            [
                'title' => 'Reportar faltantes',
                'description' => 'Documentar productos faltantes en anaquel y reportar a supervisor.',
                'priority' => 'high',
                'category' => 'inventory',
                'estimated_minutes' => 20,
                'requires_evidence' => true,
                'evidence_type' => 'document',
                'evidence_instructions' => 'Subir lista de faltantes con fotos.',
                'created_by' => 1,
            ],
            [
                'title' => 'Subir evidencia de pasillo',
                'description' => 'Documentar con fotos el estado de limpieza y organización del pasillo asignado.',
                'priority' => 'medium',
                'category' => 'daily',
                'estimated_minutes' => 15,
                'requires_evidence' => true,
                'evidence_type' => 'photo',
                'evidence_instructions' => 'Mínimo 3 fotos del pasillo completo.',
                'created_by' => 1,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
