<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\TaskLogController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserClientController;
use App\Http\Controllers\ClientActivityController;
use App\Http\Controllers\DashboardActivityController;

// LOGIN
Auth::routes(['register' => false]);


Route::get('/', function () {
    return redirect()->guest('/login');
});

// SSO
Route::group(['middleware' => ['web', 'guest']], function(){
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::get('connect', [AuthController::class, 'connect'])->name('connect');
});

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('unauthorized', function () {
    return view('errors.401');
})->name('unauthorized');

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

/**
 *
 * REDIS CACHE CLEAR
 */
Route::GET('redis/clear-cache', function () {
    Redis::flushdb();
    echo 'redis cache cleared successfully!';
});

// HRPORTAL API
Route::GET('HREmployeeProfileAPI', [PermissionController::class, 'hrportalusers']); 

/**
 *  START OF AUTHORIZE & ACTIVE USERS
 */
Route::group(['middleware' => ['verify.access','web','active.user'],],function () {

    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('index', [HomeController::class, 'index'])->name('index');

    // Agent Task: Start / Update / Stop
    Route::get('/my-tasks/{status?}', [PageController::class, 'showAgentTasks'])->name('my-tasks.index');
    Route::group(['prefix' => 'my-task'],
            function ()
        {
            Route::get('/{status?}', [TasksController::class,'agentTask'])->name('my-task.index');
            Route::post('/store', [TasksController::class,'store'])->name('my-task.store');
            Route::get('/show/{id}', [TasksController::class,'show'])->name('my-task.show');
            Route::post('/update/{id}', [TasksController::class,'update'])->name('my-task.update');
            Route::post('/stop/{id}', [TasksController::class,'stopTask'])->name('my-task.stop');

        });

    Route::put('task/start/{taskId}', [TasksController::class, 'startTask'])->name('task.start');
    Route::put('task/updateStatus/{taskId}', [TasksController::class, 'updateTaskStatus'])->name('task.status.update');
    // Route::put('task/pause/{taskId}', [TasksController::class, 'pauseTask'])->name('task.pause');
    // Route::put('task/resume/{taskId}', [TasksController::class, 'resumeTask'])->name('task.resume');
    Route::put('task/stop/{taskId}', [TasksController::class, 'stopTask'])->name('task.stop');

    Route::resource('task', TasksController::class);
    Route::get('tasks', [PageController::class, 'showAgentTaskLists'])->name('tasks.index');

    // Client Activity Import / Export
    Route::resource('client-activities', ClientActivityController::class);
    Route::get('client-activity-upload-template', [ExportController::class, 'uploadClientActivityTemplate'])->name('upload.client-activity.template');
    Route::post('client-activity-import', [ImportController::class, 'importClientActivity'])->name('client-activity-import');

    // Report
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('export', [ExportController::class, 'export'])->name('export');

    /**
     * START OF ADMIN, TL, OM
     */

    Route::group(['middleware' => ['tlom.admin'],], function ()
        {
            // Dashboard Activity
            Route::get('dashboard-activity-upload-template', [ExportController::class, 'uploadDashboardActivityTemplate'])->name('upload.dashboard-activity.template');
            Route::post('dashboard-activity-import', [ImportController::class, 'importDashboardActivity'])->name('dashboard-activity-import');

            // Task Import / Export - removed
            Route::get('tasks-upload', [TasksController::class, 'upload'])->name('upload');
            Route::get('tasks-upload-task-template', [ExportController::class, 'uploadTasksTemplate'])->name('upload.tasks.template');
            Route::post('tasks-import', [ImportController::class, 'importTasks'])->name('tasks-import');

            // Resource
            Route::resource('clusters', ClusterController::class);
            Route::resource('clients', ClientController::class);
            Route::get('clients/get_clients/{clusterId}', [ClientController::class,'getClients'])->name('clients.get_clients');

            Route::resource('permissions', PermissionController::class);
            Route::get('permissions/get_tloms/{clusterId}', [PermissionController::class,'getTLOMs'])->name('permissions.get_tloms');

            Route::get('permissions', [PageController::class, 'showPermissions'])->name('permissions.index');
            Route::group(['prefix' => 'permission'],
            function ()
            {
                Route::get('/all', [PermissionController::class,'index'])->name('permission.index');
                Route::post('/store', [PermissionController::class,'store'])->name('permission.store');
                Route::get('/show/{id}', [PermissionController::class,'show'])->name('permission.show');
                Route::post('/update/{id}', [PermissionController::class,'update'])->name('permission.update');
                Route::post('/delete/{id}', [PermissionController::class,'destroy'])->name('permission.delete');
            });

            Route::resource('dashboard-activities', DashboardActivityController::class);
            Route::resource('user-clients', UserClientController::class);
            Route::resource('task/logs', TaskLogController::class);

        }
    );
    /**
     * END OF ADMIN, TL, OM
     */
});
/**
 * END OF AUTHORIZE & ACTIVE USERS
 *
 */

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
