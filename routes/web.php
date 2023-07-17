<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\TaskLogController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserClientController;
use App\Http\Controllers\ClientActivityController;
use App\Http\Controllers\DashboardActivityController;

// LOGIN
Auth::routes(['register' => false]);


Route::get('/', function () {
    return redirect()->guest('/login');
});

// 2FA
// Route::get('verify/resend', 'Auth\TwoFactorController@resend')->name('verify.resend');
// Route::resource('verify', 'Auth\TwoFactorController')->only(['index', 'store']);

// SSO
// Route::group(['middleware' => ['web', 'guest']], function(){
//     Route::get('login', 'Auth\AuthController@login')->name('login');
    // Route::get('connect', 'Auth\AuthController@connect')->name('connect');
// });

// Route::group(['middleware' => ['web', 'MsGraphAuthenticated']], function(){
//     Route::get('/home', 'HomeController@index')->name('home');
//     Route::get('logout', 'Auth\AuthController@logout')->name('logout');
// });

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('unauthorized', function () {
    return view('errors.401');
})->name('unauthorized');

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// Route::get('{any}', [App\Http\Controllers\HomeController::class, 'any'])->name('any');

/**
 *  START OF AUTHORIZE & ACTIVE USERS
 */
// change 2FA - twofactor >> SSO - MsGraphAuthenticated >> VerifyAccess
Route::group(['middleware' => ['auth','web'],],function () {

    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('index', [HomeController::class, 'index'])->name('index');

    // ADMIN, TL, & OM
    // Task Import / Export
    Route::get('tasks-upload', [TasksController::class, 'upload'])->name('upload');
    Route::get('tasks-upload-task-template', [ExportController::class, 'uploadTasksTemplate'])->name('upload.tasks.template');
    Route::post('tasks-import', [ImportController::class, 'importTasks'])->name('tasks-import');

    // Agent Task: Start / Update / Stop
    Route::get('my-task', [TasksController::class, 'agentTask'])->name('my-task.index');
    Route::put('task/start/{taskId}', [TasksController::class, 'startTask'])->name('task.start');
    Route::put('task/updateStatus/{taskId}', [TasksController::class, 'updateTaskStatus'])->name('task.status.update');
    Route::put('task/stop/{taskId}', [TasksController::class, 'stopTask'])->name('task.stop');

    // Report
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('export', [ExportController::class, 'export'])->name('export');

    // Client Activity Import / Export
    Route::resource('client-activities', ClientActivityController::class);
    Route::get('client-activity-upload-template', [ExportController::class, 'uploadClientActivityTemplate'])->name('upload.client-activity.template');
    Route::post('client-activity-import', [ImportController::class, 'importClientActivity'])->name('client-activity-import');

    // ADMIN ONLY
    // Resource
    Route::resource('clusters', ClusterController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('dashboard-activities', DashboardActivityController::class);
    Route::resource('user-clients', UserClientController::class);
    Route::resource('task', TasksController::class);
    Route::resource('task/logs', TaskLogController::class);
});
/**
 * END OF AUTHORIZE & ACTIVE USERS
 *
 */

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
