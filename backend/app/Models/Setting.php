<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
        'description',
        'is_editable',
        'is_visible',
    ];

    protected $casts = [
        'value' => 'json',
        'is_editable' => 'boolean',
        'is_visible' => 'boolean',
    ];

    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_JSON = 'json';
    const TYPE_DATE = 'date';
    const TYPE_DECIMAL = 'decimal';

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        $value = $setting->value;

        return match ($setting->type) {
            self::TYPE_BOOLEAN => (bool) $value,
            self::TYPE_INTEGER => (int) $value,
            self::TYPE_DECIMAL => (float) $value,
            default => $value,
        };
    }

    public static function set(string $key, $value, string $type = self::TYPE_STRING, string $category = 'general', string $description = ''): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'category' => $category,
                'description' => $description,
                'is_editable' => true,
                'is_visible' => true,
            ]
        );

        return $setting;
    }

    public static function getCompanySettings(): array
    {
        return [
            'company_name' => self::get('company_name', 'DecorArte'),
            'company_rfc' => self::get('company_rfc', ''),
            'company_address' => self::get('company_address', ''),
            'workday_hours' => self::get('workday_hours', 8),
            'workdays_per_week' => self::get('workdays_per_week', 6),
            'entry_time' => self::get('entry_time', '08:30'),
            'exit_time' => self::get('exit_time', '17:00'),
            'meal_duration' => self::get('meal_duration', 30),
            'tolerance_minutes' => self::get('tolerance_minutes', 10),
            'delays_to_absence' => self::get('delays_to_absence', 3),
            'absences_to_suspension' => self::get('absences_to_suspension', 3),
            'rehire_min_months' => self::get('rehire_min_months', 6),
            'vacation_table' => self::get('vacation_table', [
                1 => 12, 2 => 14, 3 => 16, 4 => 18, 5 => 20,
                6 => 22, 7 => 22, 8 => 22, 9 => 22, 10 => 22,
                11 => 24, 12 => 24, 13 => 24, 14 => 24, 15 => 24,
                16 => 26, 17 => 26, 18 => 26, 19 => 26, 20 => 26,
            ]),
            'christmas_bonus_days' => self::get('christmas_bonus_days', 15),
            'vacation_bonus_percent' => self::get('vacation_bonus_percent', 25),
        ];
    }
}
