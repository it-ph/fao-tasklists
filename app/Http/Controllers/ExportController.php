<?php

namespace App\Http\Controllers;

use App\Exports\TasksReportExport;
use App\Exports\UploadClientActivityTemplateExport;
use App\Exports\UploadDashboardActivityTemplateExport;
use App\Exports\UploadTaskAssignmentExport;
use App\Models\Task;
use App\Models\TaskAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $date_range_selected = explode("-", $request['daterange']);

        $request['date_from'] = trim($date_range_selected[0]);
        $request['date_to'] = trim($date_range_selected[1]);

        $this->validate($request,
        [
            'daterange' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',

        ],
        [   'daterange.required'=>'Date Range is Required!',
            'date_from.required'=>'Date From is Required!',
            'date_to.required' => 'Date To is Required!'
        ]);

        $date_from =  Carbon::parse($request['date_from'])->format('Y-m-d');
        $date_to =  Carbon::parse($request['date_to'])->format('Y-m-d');
        $task_type = $request['task_type'];

        // $tasks = Task::query()
        //     ->with([
        //         'thecluster:id,name',
        //         'theclient:id,name',
        //         'theagent:id,fullname',
        //         'theclientactivity:id,name,function'
        //     ])
        //     ->whereRaw(
        //         "shift_date >= ? AND shift_date <= ?",
        //         [
        //             $date_from." 00:00:00",
        //             $date_to." 23:59:59"
        //         ]
        //     )
        //     ->orderBy('start_date','DESC');

        if ($task_type === 'task_assignments') 
        {
            $query = TaskAssignment::query();
            $relations = [
                'thecluster:id,name', 
                'theclient:id,name', 
                'theagent:id,fullname'
            ];
            
            $dateColumn = 'schedule';
        } else 
        {
            $query = Task::query();
            $relations = [
                'thecluster:id,name', 
                'theclient:id,name', 
                'theagent:id,fullname', 
                'theclientactivity:id,name,function'
            ];
            
            $dateColumn = 'shift_date';
        }

        $tasks = $query
            ->with($relations)
            ->whereRaw("$dateColumn >= ? AND $dateColumn <= ?", [
                $date_from . ' 00:00:00', 
                $date_to . ' 23:59:59'
            ])
            ->orderBy('start_date', 'DESC');

        // admin
        if(auth()->user()->isAdmin())
        {
            $tasks = $this->getFilteredData($request['filter_by'],$request['filtered_to'],$tasks);
        }
        // operations manager
        elseif(auth()->user()->isOperationsManager())
        {
            $tasks = $tasks->OMPermission();
            $tasks = $this->getFilteredData($request['filter_by'],$request['filtered_to'],$tasks);
        }
        // team leader
        elseif(auth()->user()->isTeamLeader())
        {
            $tasks = $tasks->TLPermission();
            $tasks = $this->getFilteredData($request['filter_by'],$request['filtered_to'],$tasks);
        }
        // accountant
        elseif(auth()->user()->isAccountant())
        {
            $tasks = $tasks->AccountantPermission()->get();
        }

        // set filename based on date filter
        if($date_from == $date_to )
        {
            $filename = strtoupper($task_type)."LIST_REPORT_". $date_from .".xlsx";
        }else
        {
            $filename = strtoupper($task_type)."LIST_REPORT_". $date_from .'_to_'.$date_to.".xlsx";
        }

        return Excel::download(new TasksReportExport($tasks, $task_type), $filename);
    }

    // get data based on filters
    public function getFilteredData($filter_by,$filtered_to,$tasks)
    {
        if($filter_by == 'All')
        {
            $tasks = $tasks->get();
        }
        else if($filter_by == 'Client')
        {
            $tasks = $tasks->whereIn('client_id', $filtered_to)->get();
        }
        else if($filter_by == 'Accountant')
        {
            $tasks = $tasks->whereIn('agent_id', $filtered_to)->get();
        }

        return $tasks;
    }

    public function uploadTaskAssignmentsTemplate()
    {
        return Excel::download(new UploadTaskAssignmentExport, 'FAO-tasklists-upload-task-assignments-template.xlsx');
    }

    public function uploadClientActivityTemplate()
    {
        return Excel::download(new UploadClientActivityTemplateExport, 'activity-upload-template.xlsx');
    }

    public function uploadDashboardActivityTemplate()
    {
        return Excel::download(new UploadDashboardActivityTemplateExport, 'dashboard-activity-upload-template.xlsx');
    }
}
