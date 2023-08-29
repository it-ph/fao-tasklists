<?php

namespace App\Services;
use App\Http\AppCache\TasksCache;

class TasksServices
{
    public function load($status)
    {
        $datastorage = [];
        $tasks = TasksCache::getAgentTasks($status);

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

            $action = $value->status == "In Progress" ?
                '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" onclick=TASK.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" title="Stop Task: On Hold / Complete" onclick=TASK.show_stop('.$value->id.')><i class="fas fa-stop"></i></button>' :
                '-';

            $employee_name = $value->theagent->employeeprofile->fullname.' '.$value->theagent->employeeprofile->last_name;
            $shift_date = date("m/d/Y",strtotime($value->shift_date));
            $date_received = date("m/d/Y",strtotime($value->date_received));
            $cluster = $value->thecluster->name;
            $client = $value->theclient->name;
            $client_activity = $value->theclientactivity->name;
            $description = $value->description;
            $start_date = date("m/d/Y h:i:s a",strtotime($value->start_date));
            $end_date = $value->end_date ? date("m/d/Y h:i:s a",strtotime($value->end_date)) : '-';
            $date_completed = $value->status == "On Hold" ? '-' : ($value->end_date ? date("m/d/Y",strtotime($value->end_date)) : '-');
            $actual_handling_time = $value->actual_handling_time ? $value->actual_handling_time : '';
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
}
