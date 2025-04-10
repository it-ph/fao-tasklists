<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\TaskPause;
use Illuminate\Http\Request;
use App\Models\ClientActivity;
use App\Traits\ResponseTraits;
use App\Services\TasksServices;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\StopTaskRequest;
use Facades\App\Http\Helpers\TimeElapsedHelper;
use Facades\App\Http\Helpers\TaskHelper;
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

        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function show($id)
    {
        $result = $this->successResponse('Task retrieved successfully!');
        try {
            $result["data"] = $this->model::query()
                ->with([
                    'theagent:emp_id,fullname,last_name',
                    'thecluster:id,name',
                    'theclient:id,name',
                    'theclientactivity:id,name'
                ])
                ->where('id', $id)
                ->first();
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function update(TaskRequest $request, $id)
    {
        $result = $this->successResponse('Task updated successfully!');
        try {
            $actual_handling_time = $request['actual_handling_time'];
            list($days, $hours, $minutes, $seconds) = explode(":", $actual_handling_time);
            $request['aht_in_minutes'] = number_format(($days * 24 * 60 + $hours * 60 + $minutes + $seconds / 60),2);

            $this->model->findOrfail($id)->update($request->all());

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

            $task->update([
                'status' => $status,
            ]);

            // create task pauses
            $task_pause = TaskPause::create([
                'task_id' => $task->id,
                'start' => Carbon::now(),
                'end' => null,
                'created_by' => auth()->user()->emp_id,
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
            $now = Carbon::now();
            $volume = $request['volume'];
            $remarks = $request['remarks'];

            $start_at = $task->start_date;
            $end_at = $now->format('Y-m-d H:i:s');
            $shift_start = '00:00:00';
            $shift_end = '23:59:59';
            $pauses = [];
            $events = []; //retain as empty array since there is no events module in the system

            $pauses = $this->getTaskPauses($task->id);
            $working_hours = TimeElapsedHelper::calculateWorkingTime($start_at, $end_at, $shift_start, $shift_end, $pauses, $events);
            $actual_handling_time = TimeElapsedHelper::convertTime($working_hours);
            $aht_in_minutes = number_format(($working_hours * 60),2);

            $task->update([
                'status' => $status,
                'end_date' => $now,
                'actual_handling_time' => $actual_handling_time,
                'aht_in_minutes' => $aht_in_minutes,
                'volume' => $volume,
                'remarks' => $remarks
            ]);

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    // get task pauses
    public function getTaskPauses($task_id) {
        $pauses = TaskPause::query()
            ->select('id','task_id','start','end')
            ->where('task_id', $task_id)
            ->get();

        if($pauses->count() > 0)
        {
            foreach($pauses as $value)
            {
                $datastorage[] = [
                    'start' => new DateTime($value->start),
                    'end' => new DateTime($value->end)
                ];
            }
            return $datastorage;
        }
        else
        {
            return [];
        }
    }
}
