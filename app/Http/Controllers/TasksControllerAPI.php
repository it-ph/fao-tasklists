<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\TaskPause;
use Illuminate\Http\Request;
use App\Models\AllowedEditingDate;
use Facades\App\Http\Helpers\TimeElapsedHelper;

class TasksControllerAPI extends Controller
{
    // FOR TESTING ONLY

    public function getTimeTaken() {
        $working_hours = TimeElapsedHelper::getWorkingHours();
        $hms = TimeElapsedHelper::convertTime($working_hours);

        // return $working_hours;
        return $hms;
    }

    //GET AGENT TASKS
    public function getAgentTasks(Request $request)
    {
        $status = $request['status'];
        if($request->ajax())
        {
            $agent_id = auth()->user()->id;

            $tasks = Task::query()
                ->with([
                    'theagent:id,fullname',
                    'thecluster:id,name',
                    'theclient:id,name',
                    'theclientactivity:id,name,function'
                ])
                ->where('agent_id', $agent_id)
                ->select('tasks.*');

            // filter by status
            if (in_array($status, (['all']))) {
                $tasks = $tasks;
            } else {
                $tasks = $tasks->where('status', $status);
            }

            return datatables($tasks)
                ->editColumn('status', (function($value){
                    $statusClass = '';
                    switch ($value->status) {
                        case 'In Progress':
                            $statusClass = 'text-success';
                            break;
                        case 'On Hold':
                            $statusClass = 'text-warning';
                            break;
                        case 'Completed':
                            $statusClass = 'text-primary';
                            break;
                        default:
                            break;
                    }

                    $status = '<span class="' . $statusClass . '"><strong>' . $value->status . '</strong></span>';
                    return $status;
                }))
                ->editColumn('agent_id', function ($value) {
                        return $value->theagent->fullname;
                })
                ->editColumn('shift_date', (function($value){
                    return $value->shift_date ? date("Y-m-d",strtotime($value->shift_date)) : '';
                }))
                ->editColumn('date_received', (function($value){
                    return $value->date_received ? date("Y-m-d",strtotime($value->date_received)) : '';
                }))
                ->editColumn('start_date', (function($value){
                    return $value->start_date ? date("Y-m-d h:i:s a",strtotime($value->start_date)) : '';
                }))
                ->editColumn('end_date', (function($value){
                    return $value->end_date ? date("Y-m-d h:i:s a",strtotime($value->end_date)) : '-';
                }))
                ->editColumn('actual_handling_time', (function($value){
                    $now = Carbon::now();
                    $actual_handling_timer = $value->start_date->diff($now)->format('%D:%H:%I:%S');
                    if($value->status <> "Completed")
                    {
                        $start_at = $value->start_date;
                        $end_at = $now->format('Y-m-d H:i:s');
                        $shift_start = '00:00:00';
                        $shift_end = '23:59:59';
                        $pauses = [];
                        $events = []; //retain as empty array since there is no events module in the system

                        $pauses = $this->getTaskPauses($value->id);
                        $working_hours = TimeElapsedHelper::calculateWorkingTime($start_at, $end_at, $shift_start, $shift_end, $pauses, $events);
                        $actual_handling_time = TimeElapsedHelper::convertTime($working_hours);
                    }
                    else
                    {
                        $actual_handling_time = $value->actual_handling_time ? $value->actual_handling_time : $actual_handling_timer;
                    }
                    return $actual_handling_time;
                }))
                ->addColumn('date_completed', (function($value){
                    return $value->status == "On Hold" ? '-' : ($value->end_date ? date("Y-m-d", strtotime($value->end_date)) : '-');
                }))
                ->addColumn('action', (function($value){
                    $action = '';
                    switch ($value->status) {
                        case 'In Progress':
                            $action = '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" onclick=TASK.show(' . $value->id . ')><i class="fas fa-pencil-alt"></i></button>
                                <button type="button" class="btn btn-info btn-sm waves-effect waves-light" title="Pause Task: On Hold" onclick=TASK.show_pause(' . $value->id . ')><i class="fas fa-pause"></i></button>
                                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" title="Stop Task: Complete" onclick=TASK.show_stop(' . $value->id . ')><i class="fas fa-stop"></i></button>';
                            break;
                        case 'On Hold':
                            $action = '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" onclick=TASK.show(' . $value->id . ')><i class="fas fa-pencil-alt"></i></button>
                                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" title="Resume Task" onclick=TASK.show_resume(' . $value->id . ') id="btn-resume-'. $value->id.'"><i class="fas fa-play"></i></button>';
                            break;
                        case 'Completed':
                            $allowed_daterange = AllowedEditingDate::first();
                            $date_from = date('Y-m-d H:i:s', strtotime($allowed_daterange->allowed_date_from));
                            $date_to = date('Y-m-d H:i:s', strtotime($allowed_daterange->allowed_date_to));
                            $shift_date = date('Y-m-d H:i:s', strtotime($value->shift_date));

                            $is_allowed_to_edit = ($shift_date >= $date_from && $shift_date <= $date_to) ? 1 : 0;
                            $action = $is_allowed_to_edit ? '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" onclick=TASK.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>' : '-';
                            break;
                        default:
                            break;
                    }
                    return $action;
                }))
                ->rawColumns(
                [
                    'action',
                ])
                ->escapeColumns([])
                ->make(true);
        }
    }

    //GET ALL AGENT TASKS
    public function getAllTasks(Request $request)
    {
        $status = $request['status'];
        if($request->ajax())
        {
            $tasks = Task::query()
                ->with([
                    'theagent:id,fullname',
                    'thecluster:id,name',
                    'theclient:id,name',
                    'theclientactivity:id,name,function'
                ])
                ->select('tasks.*');

            // Get user permission
            $userPermission = auth()->user()->permission;

            // Filter tasks based on user permission
            switch ($userPermission) {
                case 'superadmin':
                case 'admin':
                    $tasks = $tasks;
                    break;
                case 'operations manager':
                    $tasks = $tasks->OMPermission();
                    break;
                case 'team leader':
                    $tasks = $tasks->TLPermission();
                    break;
                default:
                    break;
            }

            // filter by status
            if (in_array($status, (['all']))) {
                $tasks = $tasks;
            } else {
                $tasks = $tasks->where('status', $status);
            }

            return datatables($tasks)
                ->editColumn('status', (function($value){
                    $statusClass = '';
                    switch ($value->status) {
                        case 'In Progress':
                            $statusClass = 'text-success';
                            break;
                        case 'On Hold':
                            $statusClass = 'text-warning';
                            break;
                        case 'Completed':
                            $statusClass = 'text-primary';
                            break;
                        default:
                            break;
                    }

                    $status = '<span class="' . $statusClass . '"><strong>' . $value->status . '</strong></span>';
                    return $status;
                }))
                ->editColumn('agent_id', function ($value) {
                        return $value->theagent->fullname;
                })
                ->editColumn('shift_date', (function($value){
                    return $value->shift_date ? date("Y-m-d",strtotime($value->shift_date)) : '';
                }))
                ->editColumn('date_received', (function($value){
                    return $value->date_received ? date("Y-m-d",strtotime($value->date_received)) : '';
                }))
                ->editColumn('start_date', (function($value){
                    return $value->start_date ? date("Y-m-d h:i:s a",strtotime($value->start_date)) : '';
                }))
                ->editColumn('end_date', (function($value){
                    return $value->end_date ? date("Y-m-d h:i:s a",strtotime($value->end_date)) : '-';
                }))
                ->editColumn('actual_handling_time', (function($value){
                    $now = Carbon::now();
                    $actual_handling_timer = $value->start_date->diff($now)->format('%D:%H:%I:%S');
                    if($value->status <> "Completed")
                    {
                        $start_at = $value->start_date;
                        $end_at = $now->format('Y-m-d H:i:s');
                        $shift_start = '00:00:00';
                        $shift_end = '23:59:59';
                        $pauses = [];
                        $events = []; //retain as empty array since there is no events module in the system

                        $pauses = $this->getTaskPauses($value->id);
                        $working_hours = TimeElapsedHelper::calculateWorkingTime($start_at, $end_at, $shift_start, $shift_end, $pauses, $events);
                        $actual_handling_time = TimeElapsedHelper::convertTime($working_hours);
                    }
                    else
                    {
                        $actual_handling_time = $value->actual_handling_time ? $value->actual_handling_time : $actual_handling_timer;
                    }
                    return $actual_handling_time;
                }))
                ->addColumn('date_completed', (function($value){
                    return $value->status == "On Hold" ? '-' : ($value->end_date ? date("Y-m-d", strtotime($value->end_date)) : '-');
                }))
                ->addColumn('action', (function($value){
                    $action = '-';
                    if($value->status == 'Completed')
                    {
                        $allowed_daterange = AllowedEditingDate::first();
                        $date_from = date('Y-m-d H:i:s', strtotime($allowed_daterange->allowed_date_from));
                        $date_to = date('Y-m-d H:i:s', strtotime($allowed_daterange->allowed_date_to));
                        $shift_date = date('Y-m-d H:i:s', strtotime($value->shift_date));

                        $is_allowed_to_edit = ($shift_date >= $date_from && $shift_date <= $date_to) ? 1 : 0;
                        $action = $is_allowed_to_edit ? '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" onclick=TASK.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>' : '-';
                    }
                    return $action;
                }))
                ->rawColumns(
                [
                    'action',
                ])
                ->escapeColumns([])
                ->make(true);
        }
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
