<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Exports\TasksReportExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UploadTasksTemplateExport;
use App\Exports\UploadClientActivityTemplateExport;
use App\Exports\UploadDashboardActivityTemplateExport;

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

        $tasks = Task::query()
            ->with([
                'thecluster:id,name',
                'theclient:id,name',
                'theagent:emp_id,email,fullname,last_name',
                'theclientactivity:id,name'
            ])
            ->whereRaw(
                "shift_date >= ? AND shift_date <= ?",
                [
                    $date_from." 00:00:00",
                    $date_to." 23:59:59"
                ]
            )
            ->orderBy('start_date','DESC');

        // admin
        if(Auth::user()->isAdmin())
        {
            $tasks = $this->getFilteredData($request['filter_by'],$request['filtered_to'],$tasks);
        }
        // operations manager
        elseif(Auth::user()->isOperationsManager())
        {
            $tasks = $tasks->OMPermission();
            $tasks = $this->getFilteredData($request['filter_by'],$request['filtered_to'],$tasks);
        }
        // team leader
        elseif(Auth::user()->isTeamLeader())
        {
            $tasks = $tasks->TLPermission();
            $tasks = $this->getFilteredData($request['filter_by'],$request['filtered_to'],$tasks);
        }
        // accountant
        elseif(Auth::user()->isAccountant())
        {
            $tasks = $tasks->AccountantPermission()->get();
        }

        // set filename based on date filter
        if($date_from == $date_to )
        {
            $filename = "TASKLISTS_REPORT_". $date_from .".xlsx";
        }else
        {
            $filename = "TASKLISTS_REPORT_". $date_from .'_to_'.$date_to.".xlsx";
        }

        return Excel::download(new TasksReportExport($tasks), $filename);
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

    public function uploadTasksTemplate()
    {
        return Excel::download(new UploadTasksTemplateExport, 'FAO-tasklists-upload-template.xlsx');
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
