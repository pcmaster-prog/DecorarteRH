<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountSuspension;

class AccountSuspensionSeeder extends Seeder
{
    public function run(): void
    {
        AccountSuspension::create([
            'employee_id' => 8,
            'triggered_by' => 1,
            'trigger_reason' => 'Acumulación de 3 faltas injustificadas en periodo de 7 días.',
            'absence_count' => 3,
            'delay_count' => 0,
            'suspended_at' => now()->subDays(1),
            'is_active' => true,
        ]);
    }
}
