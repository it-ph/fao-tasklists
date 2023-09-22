<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\ClientActivity;
use App\Traits\ResponseTraits;
use App\Services\TasksServices;
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
            $request['created_by'] = Auth::id();
            $request['start_date'] = Carbon::now();
            $this->model->create($request->all());

            // clear cache
            Redis::del('in_progress_tasks_of_agent_'.Auth::id());
            Redis::del('all_tasks_of_agent_'.Auth::id());

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
            Redis::del('in_progress_tasks_of_agent_'.Auth::id());
            Redis::del('all_tasks_of_agent_'.Auth::id());

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function upload()
    {
        return view('pages.admin.tasks.upload');
    }

    public function stopTask(StopTaskRequest $request, $id)
    {
        $result = $this->successResponse("Task has been ".$request['status']." successfully!");
        try {
            $task = $this->model->findOrfail($id);
            $status = $request['status'];
            $actual_handling_time = "";
            $volume = $request['volume'];
            $remarks = $request['remarks'];

            $start = Carbon::parse($task->start_date);
            $now = Carbon::now();
            $actual_handling_time = $now->diff($start)->format('%D:%H:%I:%S');

            $task->update([
                'status' => $status,
                'end_date' => Carbon::now(),
                'actual_handling_time' => $actual_handling_time,
                'volume' => $volume,
                'remarks' => $remarks
            ]);

            // clear cache
            Redis::del('in_progress_tasks_of_agent_'.Auth::id());
            Redis::del($status.'_tasks_of_agent_'.Auth::id());
            Redis::del('all_tasks_of_agent_'.Auth::id());

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    // Pause Task
    public function pauseTask(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $status = "On Hold";
        // $actual_handling_time = "";
        // $volume = $request['volume'];
        // $remarks = $request['remarks'];

        // $start = Carbon::parse($task->start_date);
        // $now = Carbon::now();
        // $actual_handling_time = $now->diff($start)->format('%D:%H:%I:%S');

        $task->update([
            'status' => $status,
            // 'end_date' => Carbon::now(),
            // 'actual_handling_time' => $actual_handling_time,
            // 'volume' => $volume,
            // 'remarks' => $remarks
        ]);

        return redirect()->back()->with('with_success', "Task has been completed successfully!");
    }

    // Resume Task
    public function resumeTask(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $status = "In Progress";
        // $actual_handling_time = "";
        // $volume = $request['volume'];
        // $remarks = $request['remarks'];

        // $start = Carbon::parse($task->start_date);
        // $now = Carbon::now();
        // $actual_handling_time = $now->diff($start)->format('%D:%H:%I:%S');

        $task->update([
            'status' => $status,
            // 'end_date' => Carbon::now(),
            // 'actual_handling_time' => $actual_handling_time,
            // 'volume' => $volume,
            // 'remarks' => $remarks
        ]);

        return redirect()->back()->with('with_success', "Task has been completed successfully!");
    }
}
