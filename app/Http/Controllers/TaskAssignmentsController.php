<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\TaskAssignment;
use App\Models\TaskAssignmentPause;
use Illuminate\Http\Request;
use App\Traits\ResponseTraits;
use App\Http\Requests\UpdateTaskAssignmentRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StopTaskAssignmentRequest;
use Facades\App\Http\Helpers\TaskHelper;
use Facades\App\Http\Helpers\TimeElapsedHelper;
use App\Http\Controllers\GlobalVariableController;

class TaskAssignmentsController extends GlobalVariableController
{
    use ResponseTraits;

    public function __construct()
    {
        parent::__construct();
        $this->model = new TaskAssignment();
    }

    public function show($id)
    {
        $result = $this->successResponse('Task retrieved successfully!');
        try {
            $result["data"] = $this->model::query()
                ->with([
                    'theagent:id,fullname',
                    'thecluster:id,name',
                    'theclient:id,name',
                ])
                ->where('id', $id)
                ->first();
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function update(UpdateTaskAssignmentRequest $request, $id)
    {
        $result = $this->successResponse('Task updated successfully!');
        try {
            if($request['status'] == 'Completed')
            {
                $actual_handling_time = $request['actual_handling_time'];
                list($days, $hours, $minutes, $seconds) = explode(":", $actual_handling_time);
                $request['aht_in_minutes'] = number_format(($days * 24 * 60 + $hours * 60 + $minutes + $seconds / 60),2);
            }

            $this->model->findOrfail($id)->update($request->all());

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    // Start Task
    public function startTask(Request $request, $id)
    {
        $request['status'] = 'In Progress';
        $result = $this->successResponse("Task status updated to: <br><strong>" . $request['status'] . "</strong>");
        try {
            $task = $this->model->findOrfail($id);
            
            $isInProgress = $task->status === 'In Progress';
            if ($isInProgress) {
                throw new \Exception("This task is already In Progress.");
            }

            $hasActiveTask = TaskHelper::hasInProgressTask();
            if ($hasActiveTask) {
                throw new \Exception("Please On Hold or Complete your current task before you can create, start or resume another task!");
            }

            $status = $request['status'];
            $now = Carbon::now();

            $task->update([
                'start_date' => $now,
                'status' => $status,
            ]);

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    // Pause Task
    public function pauseTask(Request $request, $id)
    {
        $request['status'] = 'On Hold';
        $result = $this->successResponse("Task status updated to: <br><strong>" . $request['status'] . "</strong>");
        try {
            $task = $this->model->findOrfail($id);
            $isOnHold = $task->status === 'On Hold';
            if ($isOnHold) {
                throw new \Exception("This task is already On Hold.");
            }

            $status = $request['status'];

            $task->update([
                'status' => $status,
            ]);

            // create task pauses
            $task_pause = TaskAssignmentPause::create([
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
        $result = $this->successResponse("Task status updated to: <br><strong>" . $request['status'] . "</strong>");
        try {
            $task = $this->model->findOrfail($id);

            $isInProgress = $task->status === 'In Progress';
            if ($isInProgress) {
                throw new \Exception("This task is already In Progress.");
            }

            $hasActiveTask = TaskHelper::hasInProgressTask();
            if ($hasActiveTask) {
                throw new \Exception("Please On Hold or Complete your current task before you can create, start or resume another task!");
            }

            $status = $request['status'];
            $now = Carbon::now();

            $task->update([
                'status' => $status,
            ]);

            // stop task pause
            $task_pause = TaskAssignmentPause::latest()->where('task_id',$task->id)->first();
            $task_pause->update([
                'end' => $now,
            ]);

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function stopTask(StopTaskAssignmentRequest $request, $id)
    {
        $result = $this->successResponse("Task status updated to: <br><strong>" . $request['status'] . "</strong>");
        try {
            $task = $this->model->findOrfail($id);

            $isCompleted = $task->status === 'Completed';
            if ($isCompleted) {
                throw new \Exception("This task has already been completed.");
            }

            $status = $request['status'];
            $now = Carbon::now();
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

            $scheduleStr = substr($task->schedule, 0, 10);
            $endDateStr = substr($task->end_date, 0, 10);
            $timeliness = ($endDateStr > $scheduleStr) ? 'Red' : 'Green';

            $task->update([
                'status' => $status,
                'end_date' => $now,
                'actual_handling_time' => $actual_handling_time,
                'aht_in_minutes' => $aht_in_minutes,
                'timeliness' => $timeliness,
                'remarks' => $remarks
            ]);

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    // get task pauses
    public function getTaskPauses($task_id) {
        $pauses = TaskAssignmentPause::query()
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
