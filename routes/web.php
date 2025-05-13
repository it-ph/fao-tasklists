<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\TaskLogController;
use App\Http\Controllers\UserControllerAPI;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TasksControllerAPI;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClientControllerAPI;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClusterControllerAPI;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserClientController;
use App\Http\Controllers\PermissionControllerAPI;
use App\Http\Controllers\ClientActivityController;
use App\Http\Controllers\DashboardActivityController;

// LOGIN
Auth::routes(['register' => false]);


Route::get('/', function () {
    return redirect()->guest('/login');
});

// SSO
Route::group(['middleware' => ['web', 'guest']], function(){
    Route::get('login', [AuthController::class, 'login'])->name('login')->middleware('csp');
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

// GET TIMETAKE HELPER TEST
Route::GET('jobs/timetaken', [TasksControllerAPI::class, 'getTimeTaken']);

/**
 *  START OF AUTHORIZE & ACTIVE USERS
 */
Route::group(['middleware' => ['verify.access','web','active.user'],],function () {

    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('index', [HomeController::class, 'index'])->name('index');

    // Users' Activities
    Route::get('activities', [ClientActivityController::class, 'showActivities'])->name('activities');

    // Agent Task: Start / Update / Stop
    Route::get('/my-tasks/{status?}', [PageController::class, 'showAgentTasks'])->name('my-tasks.index');
    Route::group(['prefix' => 'my-task'],
            function ()
        {
            Route::post('api/{status?}', [TasksControllerAPI::class,'getAgentTasks'])->name('api.get.my-task');
            Route::get('/{status?}', [TasksController::class,'agentTask'])->name('my-task.index');
            Route::post('/store', [TasksController::class,'store'])->name('my-task.store');
            Route::get('/show/{id}', [TasksController::class,'show'])->name('my-task.show');
            Route::post('/update/{id}', [TasksController::class,'update'])->name('my-task.update');
            Route::post('/stop/{id}', [TasksController::class,'stopTask'])->name('my-task.stop');
            Route::post('/pause/{id}', [TasksController::class,'pauseTask'])->name('my-task.pause');
            Route::post('/resume/{id}', [TasksController::class,'resumeTask'])->name('my-task.resume');
            Route::post('/has-active-task', [TasksController::class,'hasActiveTask'])->name('has-active-task');
        });

    Route::put('task/start/{taskId}', [TasksController::class, 'startTask'])->name('task.start');
    Route::put('task/updateStatus/{taskId}', [TasksController::class, 'updateTaskStatus'])->name('task.status.update');
    // Route::put('task/pause/{taskId}', [TasksController::class, 'pauseTask'])->name('task.pause');
    Route::put('task/resume/{taskId}', [TasksController::class, 'resumeTask'])->name('task.resume');
    Route::put('task/stop/{taskId}', [TasksController::class, 'stopTask'])->name('task.stop');

    Route::resource('task', TasksController::class);
    Route::get('tasks', [PageController::class, 'showAgentTaskLists'])->name('tasks.index');
    Route::post('tasks/api/{status?}', [TasksControllerAPI::class, 'getAllTasks'])->name('api.get.tasks');

    // Client Activity Import / Export
    Route::resource('client-activities', ClientActivityController::class);
    // Route::group(['prefix' => 'client-activity'],
    // function () {
    //     Route::post('api/all', [ClientActivityControllerAPI::class, 'getAllClients'])->name('api.get.client-activities');
    //     Route::get('/all', [ClientActivityController::class, 'index'])->name('client-activity.index');
    //     Route::post('/store', [ClientActivityController::class, 'store'])->name('client-activity.store');
    //     Route::get('/show/{id}', [ClientActivityController::class, 'show'])->name('client-activity.show');
    //     Route::post('/update/{id}', [ClientActivityController::class, 'update'])->name('client-activity.update');
    //     Route::post('/delete/{id}', [ClientActivityController::class, 'destroy'])->name('client-activity.delete');
    // });

    Route::get('client-activity-upload-template', [ExportController::class, 'uploadClientActivityTemplate'])->name('upload.client-activity.template');
    Route::post('client-activity-import', [ImportController::class, 'importClientActivity'])->name('client-activity-import');

    // Report
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('export', [ExportController::class, 'export'])->name('export');

    // Shift Date Setting
    Route::post('shift-date', [UserController::class, 'updateShiftDate'])->name('shift-date.update');

    // Dashboard
    Route::group(['prefix' => 'dashboard/report'],
        function () {
            Route::post('daily', [DashboardController::class,'loadDaily']);
            Route::post('weekly', [DashboardController::class, 'loadWeekly']);
            Route::post('monthly', [DashboardController::class, 'loadMonthly']);
            Route::post('yearly', [DashboardController::class, 'loadYearly']);
        }
    );

    // Change Requests
    Route::get('/change-requests', [PageController::class, 'showChangeRequests'])->name('change-requests.index');
    Route::group(['prefix' => 'change-request'],
    function ()
    {
        Route::post('api/all', [ChangeRequestControllerAPI::class,'getAllChangeRequests'])->name('api.get.change-requests');
        Route::get('/all', [ChangeRequestController::class,'index'])->name('change-request.index');
        Route::post('/store', [ChangeRequestController::class,'store'])->name('change-request.store');
        Route::get('/show/{id}', [ChangeRequestController::class,'show'])->name('change-request.show');
        Route::post('/update/{id}', [ChangeRequestController::class,'update'])->name('change-request.update');
        Route::post('/delete/{id}', [ChangeRequestController::class,'destroy'])->name('change-request.delete');
    });



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

            // Clusters
            Route::get('/clusters', [PageController::class, 'showClusters'])->name('clusters.index');
            Route::group(['prefix' => 'cluster'],
            function ()
            {
                Route::post('api/all', [ClusterControllerAPI::class,'getAllClusters'])->name('api.get.clusters');
                Route::get('/all', [ClusterController::class,'index'])->name('cluster.index');
                Route::post('/store', [ClusterController::class,'store'])->name('cluster.store');
                Route::get('/show/{id}', [ClusterController::class,'show'])->name('cluster.show');
                Route::post('/update/{id}', [ClusterController::class,'update'])->name('cluster.update');
                Route::post('/delete/{id}', [ClusterController::class,'destroy'])->name('cluster.delete');
            });

            // Client
            Route::resource('clients', ClientController::class);
            Route::group(['prefix' => 'client'],
            function () {
                Route::post('api/all', [ClientControllerAPI::class, 'getAllClients'])->name('api.get.clients');
                Route::get('/all', [ClientController::class, 'index'])->name('client.index');
                Route::post('/store', [ClientController::class, 'store'])->name('client.store');
                Route::get('/show/{id}', [ClientController::class, 'show'])->name('client.show');
                Route::post('/update/{id}', [ClientController::class, 'update'])->name('client.update');
                Route::post('/delete/{id}', [ClientController::class, 'destroy'])->name('client.delete');
            });

            Route::get('clients/get_clients/{clusterId}', [ClientController::class,'getClients'])->name('clients.get_clients');

            // Route::resource('permissions', PermissionController::class);
            // Route::get('permissions/get_tloms/{clusterId}', [PermissionController::class,'getTLOMs'])->name('permissions.get_tloms');
            // Route::get('permissions/get_accountants/{userId}', [PermissionController::class,'getAccountants'])->name('permissions.get_accountants');

            // Route::get('permissions', [PageController::class, 'showPermissions'])->name('permissions.index');
            // Route::group(['prefix' => 'permission'],
            // function ()
            // {
            //     Route::post('api/all', [PermissionControllerAPI::class,'getAllUsers'])->name('api.get.users');
            //     Route::get('/all', [PermissionController::class,'index'])->name('permission.index');
            //     Route::post('/store', [PermissionController::class,'store'])->name('permission.store');
            //     Route::get('/show/{id}', [PermissionController::class,'show'])->name('permission.show');
            //     Route::post('/update/{id}', [PermissionController::class,'update'])->name('permission.update');
            //     Route::post('/delete/{id}', [PermissionController::class,'destroy'])->name('permission.delete');
            // });

            Route::get('users/get_tloms/{clusterId}', [UserController::class,'getTLOMs'])->name('users.get_tloms');
            Route::get('users/get_accountants/{userId}', [UserController::class,'getAccountants'])->name('users.get_accountants');

            Route::get('users', [PageController::class, 'showUsers'])->name('users.index');
            Route::group(['prefix' => 'user'],
            function ()
            {
                Route::post('api/all', [UserControllerAPI::class,'getAllUsers'])->name('api.get.users');
                Route::get('/all', [UserController::class,'index'])->name('user.index');
                Route::post('/store', [UserController::class,'store'])->name('user.store');
                Route::get('/show/{id}', [UserController::class,'show'])->name('user.show');
                Route::post('/update/{id}', [UserController::class,'update'])->name('user.update');
                Route::post('/delete/{id}', [UserController::class,'destroy'])->name('user.delete');
            });

            Route::resource('dashboard-activities', DashboardActivityController::class);
            Route::resource('user-clients', UserClientController::class);
            Route::resource('task/logs', TaskLogController::class);
        }
    );
    /**
     * END OF ADMIN, TL, OM
     */

    /**
     * START OF ADMIN ONLY
     */
        Route::resource('settings', SettingsController::class)->middleware('admin');
    /**
     * END OF ADMIN ONLY
     */

});
/**
 * END OF AUTHORIZE & ACTIVE USERS
 *
 */

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
