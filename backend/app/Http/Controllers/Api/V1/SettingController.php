<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::where('is_visible', true)->get();

        return response()->json([
            'data' => $settings->groupBy('category')->map(function ($group) {
                return $group->map(function ($setting) {
                    return [
                        'key' => $setting->key,
                        'value' => $setting->value,
                        'type' => $setting->type,
                        'description' => $setting->description,
                        'is_editable' => $setting->is_editable,
                    ];
                });
            }),
        ]);
    }

    public function company()
    {
        return response()->json([
            'data' => Setting::getCompanySettings(),
        ]);
    }

    public function update(Request $request, $key)
    {
        $setting = Setting::where('key', $key)->firstOrFail();

        if (!$setting->is_editable) {
            return response()->json(['message' => 'Esta configuración no puede ser editada'], 403);
        }

        $request->validate(['value' => 'required']);
        $setting->update(['value' => $request->value]);

        return response()->json(['message' => 'Configuración actualizada', 'setting' => $setting]);
    }
}
