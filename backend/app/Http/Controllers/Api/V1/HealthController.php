<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function check()
    {
        try {
            DB::connection()->getPdo();
            return response()->json([
                'status' => 'healthy',
                'database' => 'connected',
                'timestamp' => now()->toIso8601String(),
                'version' => '1.0.0',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'unhealthy',
                'database' => 'disconnected',
                'error' => $e->getMessage(),
            ], 503);
        }
    }
}
