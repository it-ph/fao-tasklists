<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\TaskPause;
use Illuminate\Http\Request;
use App\Models\ClientActivity;
use App\Traits\ResponseTraits;
use App\Services\TasksServices;
use Facades\App\Http\Helpers\TaskHelper;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\StopTaskRequest;
use App\Http\Controllers\GlobalVariableController;

class TasksController extends GlobalVariableController
{
    use ResponseTraits;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Task();
        $this->service = new TasksServices();
    }

    // AGENT ACCESS
    public function agentTask(Request $request)
    {
        $status = $request['status'];
        $result = $this->successResponse('Tasks loaded successfully!');
        try {
            $result["data"] = $this->service->load($status);

        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    // ADMIN, TL, & OM ACCESS
    public function index(Request $request)
    {
        $result = $this->successResponse('Tasks loaded successfully!');
        $status = $request['status'];

        try {
            $result["data"] = $this->service->loadTasklists($status);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function store(TaskRequest $request)
    {
        $result = $this->successResponse('Task created successfully!');
        try {
            $request['created_by'] = Auth::user()->emp_id;
            $request['start_date'] = Carbon::now();
            $this->model->create($request->all());

            // clear cache
            // Redis::del('in_progress_tasks_of_agent_'.Auth::user()->emp_id);
            // Redis::del('all_tasks_of_agent_'.Auth::user()->emp_id);

        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function show($id)
    {
        $result = $this->successResponse('Task retrieved successfully!');
        try {
            $result["data"] = $this->model::findOrfail($id);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function update(TaskRequest $request, $id)
    {
        $result = $this->successResponse('Task updated successfully!');
        try {
            $this->model->findOrfail($id)->update($request->all());

            // clear cache
            // Redis::del('in_progress_tasks_of_agent_'.Auth::user()->emp_id);
            // Redis::del('all_tasks_of_agent_'.Auth::user()->emp_id);

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function upload()
    {
        return view('pages.admin.tasks.upload');
    }

    // Check if user has active task
    public function hasActiveTask()
    {
        $result = $this->successResponse('Task retrieved successfully!');
        try {
            $result["data"] = auth()->user()->hasActiveTask();
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    // Pause Task
    public function pauseTask(Request $request, $id)
    {
        $request['status'] = 'On Hold';
        $result = $this->successResponse("Task has been ".$request['status']." successfully!");
        try {
            $task = $this->model->findOrfail($id);
            $status = $request['status'];
            
            $values = TaskHelper::getActualHandlingTime($task);

            $task->update([
                'status' => $status,
                'old_dt' => $values['old_dt'],
                'temp_handling_time' => $values['temp_handling_time'],
                'actual_handling_time' => $values['actual_handling_time'],
            ]);

            // create task pauses
            $task_pause = TaskPause::create([
                'task_id' => $task->id,
                'start' => Carbon::now(),
                'end' => null,
                'created_by' => auth()->user()->id,
            ]);

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    // Resume Task
    public function resumeTask(Request $request, $id)
    {
        $request['status'] = 'In Progress';
        $result = $this->successResponse("Task has been ".$request['status']." successfully!");
        try {
            $task = $this->model->findOrfail($id);
            $status = $request['status'];
            $now = Carbon::now();

            $task->update([
                'status' => $status,
                'old_dt' => $now,
            ]);

            // stop task pause
            $task_pause = TaskPause::latest()->where('task_id',$task->id)->first();
            $task_pause->update([
                'end' => $now,
            ]);

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function stopTask(StopTaskRequest $request, $id)
    {
        $result = $this->successResponse("Task has been ".$request['status']." successfully!");
        try {
            $task = $this->model->findOrfail($id);
            $status = $request['status'];
            $volume = $request['volume'];
            $remarks = $request['remarks'];

            $values = TaskHelper::getActualHandlingTime($task);

            $task->update([
                'status' => $status,
                'end_date' => Carbon::now(),
                'old_dt' => $values['old_dt'],
                'temp_handling_time' => $values['temp_handling_time'],
                'actual_handling_time' => $values['actual_handling_time'],
                'volume' => $volume,
                'remarks' => $remarks
            ]);

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }
}
