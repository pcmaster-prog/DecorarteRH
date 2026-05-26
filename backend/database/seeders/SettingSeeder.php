<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'company_name', 'value' => 'DecorArte', 'type' => 'string', 'category' => 'company'],
            ['key' => 'company_rfc', 'value' => 'DEC123456ABC', 'type' => 'string', 'category' => 'company'],
            ['key' => 'company_address', 'value' => 'Av. Principal 123, Ciudad de México', 'type' => 'string', 'category' => 'company'],
            ['key' => 'company_phone', 'value' => '555-123-4567', 'type' => 'string', 'category' => 'company'],
            ['key' => 'entry_time', 'value' => '08:30', 'type' => 'string', 'category' => 'schedule'],
            ['key' => 'exit_time', 'value' => '17:00', 'type' => 'string', 'category' => 'schedule'],
            ['key' => 'payroll_day', 'value' => 'saturday', 'type' => 'string', 'category' => 'schedule'],
            ['key' => 'payroll_time_start', 'value' => '12:00', 'type' => 'string', 'category' => 'schedule'],
            ['key' => 'payroll_time_end', 'value' => '14:00', 'type' => 'string', 'category' => 'schedule'],
            ['key' => 'week_start_day', 'value' => 'sunday', 'type' => 'string', 'category' => 'schedule'],
            ['key' => 'week_end_day', 'value' => 'saturday', 'type' => 'string', 'category' => 'schedule'],
            ['key' => 'ley_silla_enabled', 'value' => false, 'type' => 'boolean', 'category' => 'compliance'],
            ['key' => 'ley_silla_seasons', 'value' => ['high'], 'type' => 'json', 'category' => 'compliance'],
            ['key' => 'theme_default', 'value' => 'light', 'type' => 'string', 'category' => 'ui'],
            ['key' => 'language_default', 'value' => 'es', 'type' => 'string', 'category' => 'ui'],
            ['key' => 'timezone_default', 'value' => 'America/Mexico_City', 'type' => 'string', 'category' => 'ui'],
            ['key' => 'notification_email_enabled', 'value' => true, 'type' => 'boolean', 'category' => 'notifications'],
            ['key' => 'notification_push_enabled', 'value' => true, 'type' => 'boolean', 'category' => 'notifications'],
            ['key' => 'max_file_size_mb', 'value' => 10, 'type' => 'integer', 'category' => 'files'],
            ['key' => 'allowed_file_types', 'value' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'], 'type' => 'json', 'category' => 'files'],
            ['key' => 'session_timeout_minutes', 'value' => 120, 'type' => 'integer', 'category' => 'security'],
            ['key' => 'password_min_length', 'value' => 8, 'type' => 'integer', 'category' => 'security'],
            ['key' => 'password_requires_special', 'value' => true, 'type' => 'boolean', 'category' => 'security'],
            ['key' => 'two_factor_enabled', 'value' => false, 'type' => 'boolean', 'category' => 'security'],
            ['key' => 'backup_frequency', 'value' => 'daily', 'type' => 'string', 'category' => 'backup'],
            ['key' => 'backup_retention_days', 'value' => 30, 'type' => 'integer', 'category' => 'backup'],
        ];

        foreach ($settings as $setting) {
            Setting::create(array_merge($setting, [
                'description' => '',
                'is_editable' => true,
                'is_visible' => true,
            ]));
        }
    }
}
