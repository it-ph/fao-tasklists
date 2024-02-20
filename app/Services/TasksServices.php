<?php

namespace App\Services;
use App\Models\Task;
use App\Http\AppCache\TasksCache;
use Facades\App\Http\Helpers\TaskHelper;
use App\Models\AllowedEditingDate;
use Illuminate\Support\Facades\Auth;

class TasksServices
{
    public function load($status)
    {
        $datastorage = [];
        // $tasks = TasksCache::getAgentTasks($status); disabled TasksCache due to realtime checking if edit of task is already locked

        $status = strtolower($status);
        $agent_id = Auth::user()->emp_id;

        $tasks = Task::query()
            ->with([
                'thecluster:id,name',
                'theclient:id,name',
                'theagent:emp_id,email,emp_code,fullname,last_name',
                'theclientactivity:id,name'
            ])
            ->where('agent_id', $agent_id);

        // filter by status
        if(in_array($status,(['all'])))
        {
            $tasks = $tasks->get();
        }
        else
        {
            $tasks = $tasks->where('status',$status)->get();
        }

        foreach($tasks as $value) {
            if ($value->status == 'In Progress') {
                $status = '<span class="text-success"><strong>'.$value->status.'</strong></span>';
            }
            else if ($value->status == 'On Hold') {
                $status = '<span class="text-warning"><strong>'.$value->status.'</strong></span>';
            }
            else if ($value->status == 'Completed') {
                $status = '<span class="text-primary"><strong>'.$value->status.'</strong></span>';
            }

            if($value->status == "In Progress")
            {
                $action = '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" onclick=TASK.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>
                    <button type="button" class="btn btn-info btn-sm waves-effect waves-light" title="Pause Task: On Hold" onclick=TASK.show_pause('.$value->id.')><i class="fas fa-pause"></i></button>
                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" title="Stop Task: Complete" onclick=TASK.show_stop('.$value->id.')><i class="fas fa-stop"></i></button>';
            }

            if($value->status == "On Hold")
            {
                $action = '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" onclick=TASK.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" title="Resume Task" onclick=TASK.show_resume('.$value->id.')><i class="fas fa-play"></i></button>';
            }

            if($value->status == "Completed")
            {
                $allowed_daterange =  AllowedEditingDate::first();
                $date_from = date('Y-m-d H:i:s', strtotime($allowed_daterange->allowed_date_from));
                $date_to = date('Y-m-d H:i:s', strtotime($allowed_daterange->allowed_date_to));
                $shift_date = date('Y-m-d H:i:s', strtotime($value->shift_date));

                if (($shift_date >= $date_from) && ($shift_date <= $date_to))
                {
                    $is_allowed_to_edit = 1; // shift date is between two dates - allowed
                }
                else
                {
                    $is_allowed_to_edit = 0; // shift date is not between two dates - not allowed / locked
                }

                $action = $is_allowed_to_edit ? '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" onclick=TASK.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>' : '-';
            }

            // $action = $value->status == "In Progress" ?
            //     '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" onclick=TASK.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>
            //     <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" title="Stop Task: On Hold / Complete" onclick=TASK.show_stop('.$value->id.')><i class="fas fa-stop"></i></button>' :
            //     '-';

            $employee_name = $value->theagent ? $value->theagent->fullname.' '.$value->theagent->last_name : "";
            $shift_date = date("m/d/Y",strtotime($value->shift_date));
            $date_received = date("m/d/Y",strtotime($value->date_received));
            $cluster = $value->thecluster->name;
            $client = $value->theclient->name;
            $client_activity = $value->theclientactivity->name;
            $description = $value->description;
            $start_date = date("m/d/Y h:i:s a",strtotime($value->start_date));
            $end_date = $value->end_date ? date("m/d/Y h:i:s a",strtotime($value->end_date)) : '-';
            $date_completed = $value->status == "On Hold" ? '-' : ($value->end_date ? date("m/d/Y",strtotime($value->end_date)) : '-');

            // START OF ACTUAL HANDLING TIME
            $now = \Carbon\Carbon::now();
            $actual_handling_timer = $value->start_date->diff($now)->format('%D:%H:%I:%S');

            // TASK ON HOLD-COMPLETED W/ ACTUAL HANDLING TIME
            if($value->actual_handling_time)
            {
                if($value->status == "In Progress")
                {
                    $get_aht = TaskHelper::getActualHandlingTime($value);
                    $actual_handling_time = $get_aht['actual_handling_time'];
                }
                else
                {
                    $actual_handling_time = $value->actual_handling_time;
                }
            }
            else
            {
                $actual_handling_time = $actual_handling_timer;
            }

            // $actual_handling_time = $value->actual_handling_time ? $value->actual_handling_time : '';
            // END OF ACTUAL HANDLING TIME

            $volume = $value->volume == 0 ? '0' : $value->volume;
            $remarks = $value->remarks ? $value->remarks : '';

            $datastorage[] = [
                'id' => $value->id,
                'status' => $status,
                'action' => $action,
                'employee_name' => $employee_name,
                'shift_date' => $shift_date,
                'date_received' => $date_received,
                'cluster' => $cluster,
                'client' => $client,
                'client_activity' => $client_activity,
                'description' => $description,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'date_completed' => $date_completed,
                'actual_handling_time' => $actual_handling_time,
                'volume' => $volume,
                'remarks' => $remarks,
            ];
        }

        return $datastorage;
    }

    public function loadTasklists($status)
    {
        $datastorage = [];
        $tasks = Task::query()
            ->with([
                'thecluster:id,name',
                'theclient:id,name',
                'theagent:id,email',
                'theagent:emp_id,fullname,last_name',
                'theclientactivity:id,name'
            ]);

        // admin
        if(Auth::user()->isAdmin())
        {
            $tasks = $tasks;
        }
        // operations manager
        elseif(Auth::user()->isOperationsManager())
        {
            $tasks = $tasks->OMPermission();
        }
        // team leader
        elseif(Auth::user()->isTeamLeader())
        {
            $tasks = $tasks->TLPermission();
        }

        // filter by status
        if(in_array($status,(['','all'])))
        {
            $tasks = $tasks->get();
        }
        else
        {
            $tasks = $tasks->where('status',$status)->get();
        }

        foreach($tasks as $value) {
            if ($value->status == 'In Progress') {
                $status = '<span class="text-success"><strong>'.$value->status.'</strong></span>';
            }
            else if ($value->status == 'On Hold') {
                $status = '<span class="text-warning"><strong>'.$value->status.'</strong></span>';
            }
            else if ($value->status == 'Completed') {
                $status = '<span class="text-primary"><strong>'.$value->status.'</strong></span>';
            }

            $employee_name = $value->theagent->fullname.' '.$value->theagent->last_name;
            $shift_date = date("m/d/Y",strtotime($value->shift_date));
            $date_received = date("m/d/Y",strtotime($value->date_received));
            $cluster = $value->thecluster->name;
            $client = $value->theclient->name;
            $client_activity = $value->theclientactivity->name;
            $description = $value->description;
            $start_date = date("m/d/Y h:i:s a",strtotime($value->start_date));
            $end_date = $value->end_date ? date("m/d/Y h:i:s a",strtotime($value->end_date)) : '-';
            $date_completed = $value->status == "On Hold" ? '-' : ($value->end_date ? date("m/d/Y",strtotime($value->end_date)) : '-');

            // START OF ACTUAL HANDLING TIME
            $now = \Carbon\Carbon::now();
            $actual_handling_timer = $value->start_date->diff($now)->format('%D:%H:%I:%S');

            // TASK ON HOLD-COMPLETED W/ ACTUAL HANDLING TIME
            if($value->actual_handling_time)
            {
                if($value->status == "In Progress")
                {
                    $get_aht = TaskHelper::getActualHandlingTime($value);
                    $actual_handling_time = $get_aht['actual_handling_time'];
                }
                else
                {
                    $actual_handling_time = $value->actual_handling_time;
                }
            }
            else
            {
                $actual_handling_time = $actual_handling_timer;
            }

            // $actual_handling_time = $value->actual_handling_time ? $value->actual_handling_time : '';
            // END OF ACTUAL HANDLING TIME

            $volume = $value->volume == 0 ? '0' : $value->volume;
            $remarks = $value->remarks ? $value->remarks : '';

            $datastorage[] = [
                'id' => $value->id,
                'status' => $status,
                'employee_name' => $employee_name,
                'shift_date' => $shift_date,
                'date_received' => $date_received,
                'cluster' => $cluster,
                'client' => $client,
                'client_activity' => $client_activity,
                'description' => $description,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'date_completed' => $date_completed,
                'actual_handling_time' => $actual_handling_time,
                'volume' => $volume,
                'remarks' => $remarks,
            ];
        }

        return $datastorage;
    }

}
