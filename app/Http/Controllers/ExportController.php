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
            'task_type' => 'required',

        ],
        [   'daterange.required'=>'Date Range is Required!',
            'date_from.required'=>'Date From is Required!',
            'date_to.required' => 'Date To is Required!',
            'task_type.required' => 'Task Type is Required!'
        ]);

        $date_from =  Carbon::parse($request['date_from'])->format('Y-m-d');
        $date_to =  Carbon::parse($request['date_to'])->format('Y-m-d');
        $task_type = $request['task_type'];

        $tasks = null;
        $task_assignments = null;

        // Helper function to apply date ranges, ordering, permissions, and custom filtering
        $fetchReportData = function($query, $dateColumn) use ($request, $date_from, $date_to) {
            $tasksQuery = $query
                ->whereRaw("$dateColumn >= ? AND $dateColumn <= ?", [
                    $date_from . ' 00:00:00', 
                    $date_to . ' 23:59:59'
                ])
                ->orderBy('start_date', 'DESC');

            if (auth()->user()->isAdmin()) {
                return $this->getFilteredData($request['filter_by'] ?? 'All', $request['filtered_to'] ?? [], $tasksQuery);
            } elseif (auth()->user()->isOperationsManager()) {
                $tasksQuery = $tasksQuery->OMPermission();
                return $this->getFilteredData($request['filter_by'] ?? 'All', $request['filtered_to'] ?? [], $tasksQuery);
            } elseif (auth()->user()->isTeamLeader()) {
                $tasksQuery = $tasksQuery->TLPermission();
                return $this->getFilteredData($request['filter_by'] ?? 'All', $request['filtered_to'] ?? [], $tasksQuery);
            } elseif (auth()->user()->isAccountant()) {
                return $tasksQuery->AccountantPermission()->get();
            }
            
            return $tasksQuery->get();
        };

        // 1. Fetch from TaskAssignment model if 'all' or 'task_assignments' is selected
        if ($task_type === 'all' || $task_type === 'task_assignments') {
            $query = TaskAssignment::query()->with([
                'thecluster:id,name', 
                'theclient:id,name', 
                'theagent:id,fullname'
            ]);
            $task_assignments = $fetchReportData($query, 'schedule');
        }

        // 2. Fetch from Task model if 'all' or 'tasks' is selected
        if ($task_type === 'all' || $task_type === 'tasks') {
            $query = Task::query()->with([
                'thecluster:id,name', 
                'theclient:id,name', 
                'theagent:id,fullname', 
                'theclientactivity:id,name,function'
            ]);
            $tasks = $fetchReportData($query, 'shift_date');
        }

        // Set filename based on date filter and selected task type
        if ($task_type === 'all') {
            $prefix = "ALL_TASKS_REPORT";
        } else {
            $prefix = strtoupper($task_type) . "LIST_REPORT";
        }

        if ($date_from == $date_to) {
            $filename = "{$prefix}_" . $date_from . ".xlsx";
        } else {
            $filename = "{$prefix}_" . $date_from . '_to_' . $date_to . ".xlsx";
        }

        return Excel::download(new TasksReportExport($tasks, $task_assignments, $task_type), $filename);
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
