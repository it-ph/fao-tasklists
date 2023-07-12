<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\TaskLogController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserClientController;
use App\Http\Controllers\ClientActivityController;
use App\Http\Controllers\DashboardActivityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['prefix' => 'admin'], function () {
//     Route::apiResource('clusters', ClusterController::class);
//     Route::apiResource('clients', ClientController::class);
//     Route::apiResource('permissions', PermissionController::class);
//     Route::apiResource('dashboard-activities', DashboardActivityController::class);
//     Route::apiResource('client-activities', ClientActivityController::class);
//     Route::apiResource('user-clients', UserClientController::class);
//     Route::apiResource('task', TasksController::class);
//     Route::apiResource('task-logs', TaskLogController::class);
// });
