<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\RolePermissionController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\SeniorityController;

Route::prefix('v1')->group(function () {
    // Auth - público
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::post('/auth/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');

    // Dashboard
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->middleware('auth:sanctum');
    Route::get('/dashboard/employee', [DashboardController::class, 'employee'])->middleware('auth:sanctum');

    // Empleados
    Route::get('/employees', [EmployeeController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/employees/{id}', [EmployeeController::class, 'show'])->middleware('auth:sanctum');
    Route::get('/employees/{id}/kardex', [EmployeeController::class, 'kardex'])->middleware('auth:sanctum');

    // Asistencia
    Route::get('/attendance', [AttendanceController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/attendance/entry', [AttendanceController::class, 'registerEntry'])->middleware('auth:sanctum');
    Route::post('/attendance/exit', [AttendanceController::class, 'registerExit'])->middleware('auth:sanctum');
    Route::post('/attendance/meal/start', [AttendanceController::class, 'startMeal'])->middleware('auth:sanctum');
    Route::post('/attendance/meal/end', [AttendanceController::class, 'endMeal'])->middleware('auth:sanctum');
    Route::get('/attendance/today', [AttendanceController::class, 'todayStatus'])->middleware('auth:sanctum');

    // Tareas
    Route::get('/tasks', [TaskController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/tasks/my', [TaskController::class, 'myTasks'])->middleware('auth:sanctum');
    Route::post('/tasks/start', [TaskController::class, 'startTask'])->middleware('auth:sanctum');
    Route::post('/tasks/complete', [TaskController::class, 'completeTask'])->middleware('auth:sanctum');
    Route::post('/tasks/assign', [TaskController::class, 'assignTask'])->middleware('auth:sanctum');

    // Roles y Permisos
    Route::get('/roles', [RolePermissionController::class, 'roles'])->middleware('auth:sanctum');
    Route::get('/permissions', [RolePermissionController::class, 'permissions'])->middleware('auth:sanctum');
    Route::get('/access-profiles', [RolePermissionController::class, 'accessProfiles'])->middleware('auth:sanctum');
    Route::get('/users/{id}/permissions', [RolePermissionController::class, 'userPermissions'])->middleware('auth:sanctum');
    Route::post('/users/simulate-access', [RolePermissionController::class, 'simulateAccess'])->middleware('auth:sanctum');
    Route::post('/permissions/temporary-grant', [RolePermissionController::class, 'grantTemporaryPermission'])->middleware('auth:sanctum');
    Route::post('/permissions/restriction', [RolePermissionController::class, 'addRestriction'])->middleware('auth:sanctum');

    // Configuraciones
    Route::get('/settings', [SettingController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/settings/company', [SettingController::class, 'company'])->middleware('auth:sanctum');
    Route::put('/settings/{key}', [SettingController::class, 'update'])->middleware('auth:sanctum');

    // Antigüedad
    Route::get('/employees/{id}/seniority', [SeniorityController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/employees/{id}/seniority', [SeniorityController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/employees/{id}/seniority/validate', [SeniorityController::class, 'validateRecord'])->middleware('auth:sanctum');
    Route::post('/employees/{id}/seniority/reopen', [SeniorityController::class, 'reopen'])->middleware('auth:sanctum');
});
