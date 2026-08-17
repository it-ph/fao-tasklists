<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\TaskAssignment;
use App\Models\TaskPause;
use Illuminate\Http\Request;
use App\Models\AllowedEditingDate;
use Facades\App\Http\Helpers\TimeElapsedHelper;

class TaskAssignmentsControllerAPI extends Controller
{
    //GET AGENT TASKS
    public function getAgentTasks(Request $request)
    {
        $status = $request['status'];
        if($request->ajax())
        {
            $agent_id = auth()->user()->id;
            $tasks = TaskAssignment::query()
                ->with([
                    'theagent:id,fullname',
                    'thecluster:id,name',
                    'theclient:id,name',
                ])
                ->where('agent_id', $agent_id)
                ->select('task_assignments.*');

            // filter by status
            if (in_array($status, (['all']))) {
                $tasks = $tasks;
            } else {
                $tasks = $tasks->where('status', $status);
            }

            return datatables($tasks)
                // 1. Custom Search for Prefix ID (e.g. TA-123, ta 123, TA123)
                ->filterColumn('id', function($query, $keyword) {
                    $keyword = trim($keyword);
                    if (preg_match('/^ta[- ]?(\d+)$/i', $keyword, $matches)) {
                        $query->where('task_assignments.id', '=', $matches[1]);
                    } else {
                        $query->where('task_assignments.id', 'like', "%{$keyword}%");
                    }
                })
                
                // 2. Custom Flexible Search for Schedule Column
                ->filterColumn('schedule', function($query, $keyword) {
                    $keyword = trim($keyword);
                    $parsedDate = $this->parseFlexibleDateSearch($keyword);
                    
                    if ($parsedDate) {
                        $query->whereDate('schedule', '=', $parsedDate);
                    } else {
                        $query->whereRaw("DATE_FORMAT(schedule, '%M') LIKE ?", ["%{$keyword}%"])
                            ->orWhereRaw("DATE_FORMAT(schedule, '%b') LIKE ?", ["%{$keyword}%"]);
                    }
                })

                // 3. Custom Flexible Search for Applicable Month Column
                ->filterColumn('applicable_month', function($query, $keyword) {
                    $keyword = trim($keyword);
                    $parsedDate = $this->parseFlexibleDateSearch($keyword);
                    
                    if ($parsedDate) {
                        $query->whereDate('applicable_month', '=', $parsedDate);
                    } else {
                        $query->whereRaw("DATE_FORMAT(applicable_month, '%M') LIKE ?", ["%{$keyword}%"])
                            ->orWhereRaw("DATE_FORMAT(applicable_month, '%b') LIKE ?", ["%{$keyword}%"]);
                    }
                })

                // 4. Custom Text Search for Standard Datetime Strings
                ->filterColumn('start_date', function($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(start_date, '%Y-%m-%d %h:%i:%s %p') LIKE ?", ["%" . trim($keyword) . "%"]);
                })
                ->filterColumn('end_date', function($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(end_date, '%Y-%m-%d %h:%i:%s %p') LIKE ?", ["%" . trim($keyword) . "%"]);
                })
                ->editColumn('status', function($value) {
                    $statusClass = '';
                    switch ($value->status) {
                        case 'Not Started':  $statusClass = 'text-secondary'; break;
                        case 'In Progress':  $statusClass = 'text-success'; break;
                        case 'On Hold':     $statusClass = 'text-warning'; break;
                        case 'Completed':   $statusClass = 'text-primary'; break;
                        default: break;
                    }
                    return '<span class="' . $statusClass . '"><strong>' . e($value->status) . '</strong></span>';
                })
                ->editColumn('agent_id', function ($value) {
                        return $value->theagent->fullname;
                })
                ->editColumn('schedule', (function($value){
                    return $value->schedule ? date("Y-m-d",strtotime($value->schedule)) : '';
                }))
                ->editColumn('applicable_month', (function($value){
                    return $value->applicable_month ? date("F Y",strtotime($value->applicable_month)) : '';
                }))
                ->editColumn('start_date', (function($value){
                    return $value->start_date ? date("Y-m-d h:i:s a",strtotime($value->start_date)) : '-';
                }))
                ->editColumn('end_date', (function($value){
                    return $value->end_date ? date("Y-m-d h:i:s a",strtotime($value->end_date)) : '-';
                }))
                ->editColumn('actual_handling_time', (function($value){
                    if($value->status === "Not Started")
                    {
                        return "00:00:00:00";
                    }
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
                ->editColumn('timeliness', function($value) { 
                    $status = $value->timeliness;
                    if (empty($status)) {
                        $scheduleStr = substr($value->schedule, 0, 10);

                        if ($value->end_date) {
                            // Task is completed: compare completion date to schedule
                            $endDateStr = substr($value->end_date, 0, 10);
                            $status = ($endDateStr > $scheduleStr) ? 'Red' : 'Green';
                        } else {
                            // Task is still running: compare current server date to schedule
                            $currentDateStr = date('Y-m-d');
                            $status = ($currentDateStr > $scheduleStr) ? 'Red' : 'Green';
                        }
                    }

                    $statusClass = ($status === 'Red') ? 'text-danger' : 'text-success';
                    return '<span class="' . $statusClass . '"><strong>' . e($status) . '</strong></span>';
                })
                ->editColumn('quality', function($value) {
                    $statusClass = '';
                    switch ($value->quality) {
                        case 'Green': $statusClass = 'text-success'; break;
                        case 'Red':   $statusClass = 'text-danger'; break;
                        default: break;
                    }
                    return !empty(trim($value->quality)) ? '<span class="' . $statusClass . '"><strong>' . e($value->quality) . '</strong></span>' : '-';
                })
                ->addColumn('date_completed', (function($value){
                    return $value->status == "On Hold" ? '-' : ($value->end_date ? date("Y-m-d", strtotime($value->end_date)) : '-');
                }))
                ->addColumn('action', (function($value){
                    $action = '';
                    switch ($value->status) {
                        case 'Not Started':
                            $action = '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" onclick=TASK.show(' . $value->id . ')><i class="fas fa-pencil-alt"></i></button>
                                <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" title="Start Task" onclick=TASK.show_start(' . $value->id . ') id="btn-start-'. $value->id.'"><i class="fas fa-play"></i></button>';
                            break;
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
                            $schedule = date('Y-m-d H:i:s', strtotime($value->schedule));

                            $is_allowed_to_edit = ($schedule >= $date_from && $schedule <= $date_to) ? 1 : 0;
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
            $tasks = TaskAssignment::query()
                ->with([
                    'theagent:id,fullname',
                    'thecluster:id,name',
                    'theclient:id,name',
                ])
                ->select('task_assignments.*');

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
                // 1. Custom Search for Prefix ID (e.g. TA-123, ta 123, TA123)
                ->filterColumn('id', function($query, $keyword) {
                    $keyword = trim($keyword);
                    if (preg_match('/^ta[- ]?(\d+)$/i', $keyword, $matches)) {
                        $query->where('task_assignments.id', '=', $matches[1]);
                    } else {
                        $query->where('task_assignments.id', 'like', "%{$keyword}%");
                    }
                })
                
                // 2. Custom Flexible Search for Schedule Column
                ->filterColumn('schedule', function($query, $keyword) {
                    $keyword = trim($keyword);
                    $parsedDate = $this->parseFlexibleDateSearch($keyword);
                    
                    if ($parsedDate) {
                        $query->whereDate('schedule', '=', $parsedDate);
                    } else {
                        $query->whereRaw("DATE_FORMAT(schedule, '%M') LIKE ?", ["%{$keyword}%"])
                            ->orWhereRaw("DATE_FORMAT(schedule, '%b') LIKE ?", ["%{$keyword}%"]);
                    }
                })

                // 3. Custom Flexible Search for Applicable Month Column
                ->filterColumn('applicable_month', function($query, $keyword) {
                    $keyword = trim($keyword);
                    $parsedDate = $this->parseFlexibleDateSearch($keyword);
                    
                    if ($parsedDate) {
                        $query->whereDate('applicable_month', '=', $parsedDate);
                    } else {
                        $query->whereRaw("DATE_FORMAT(applicable_month, '%M') LIKE ?", ["%{$keyword}%"])
                            ->orWhereRaw("DATE_FORMAT(applicable_month, '%b') LIKE ?", ["%{$keyword}%"]);
                    }
                })

                // 4. Custom Text Search for Standard Datetime Strings
                ->filterColumn('start_date', function($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(start_date, '%Y-%m-%d %h:%i:%s %p') LIKE ?", ["%" . trim($keyword) . "%"]);
                })
                ->filterColumn('end_date', function($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(end_date, '%Y-%m-%d %h:%i:%s %p') LIKE ?", ["%" . trim($keyword) . "%"]);
                })
                ->editColumn('status', function($value) {
                    $statusClass = '';
                    switch ($value->status) {
                        case 'Not Started':  $statusClass = 'text-secondary'; break;
                        case 'In Progress':  $statusClass = 'text-success'; break;
                        case 'On Hold':     $statusClass = 'text-warning'; break;
                        case 'Completed':   $statusClass = 'text-primary'; break;
                        default: break;
                    }
                    return '<span class="' . $statusClass . '"><strong>' . e($value->status) . '</strong></span>';
                })
                ->editColumn('agent_id', function ($value) {
                        return $value->theagent->fullname;
                })
                ->editColumn('schedule', (function($value){
                    return $value->schedule ? date("Y-m-d",strtotime($value->schedule)) : '';
                }))
                ->editColumn('applicable_month', (function($value){
                    return $value->applicable_month ? date("F Y",strtotime($value->applicable_month)) : '';
                }))
                ->editColumn('start_date', (function($value){
                    return $value->start_date ? date("Y-m-d h:i:s a",strtotime($value->start_date)) : '-';
                }))
                ->editColumn('end_date', (function($value){
                    return $value->end_date ? date("Y-m-d h:i:s a",strtotime($value->end_date)) : '-';
                }))
                ->editColumn('actual_handling_time', (function($value){
                    if($value->status === "Not Started")
                    {
                        return "00:00:00:00";
                    }
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
                ->editColumn('timeliness', function($value) { 
                    $status = $value->timeliness;
                    if (empty($status)) {
                        $scheduleStr = substr($value->schedule, 0, 10);

                        if ($value->end_date) {
                            // Task is completed: compare completion date to schedule
                            $endDateStr = substr($value->end_date, 0, 10);
                            $status = ($endDateStr > $scheduleStr) ? 'Red' : 'Green';
                        } else {
                            // Task is still running: compare current server date to schedule
                            $currentDateStr = date('Y-m-d');
                            $status = ($currentDateStr > $scheduleStr) ? 'Red' : 'Green';
                        }
                    }

                    $statusClass = ($status === 'Red') ? 'text-danger' : 'text-success';
                    return '<span class="' . $statusClass . '"><strong>' . e($status) . '</strong></span>';
                })
                ->editColumn('quality', function($value) {
                    $statusClass = '';
                    switch ($value->quality) {
                        case 'Green': $statusClass = 'text-success'; break;
                        case 'Red':   $statusClass = 'text-danger'; break;
                        default: break;
                    }
                    return !empty(trim($value->quality)) ? '<span class="' . $statusClass . '"><strong>' . e($value->quality) . '</strong></span>' : '-';
                })
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
                        $schedule = date('Y-m-d H:i:s', strtotime($value->schedule));

                        $is_allowed_to_edit = ($schedule >= $date_from && $schedule <= $date_to) ? 1 : 0;
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

    /**
     * Helper parser utility to translate various manual user date formats safely
     */
    private function parseFlexibleDateSearch($keyword)
    {
        // Ignore single raw text month names (like "September") to let raw SQL handle them
        if (preg_match('/^[a-zA-Z\s]+$/i', $keyword)) {
            return null;
        }

        try {
            // Catches both "09-30-2026" and "09/30/2026" in one go
            if (preg_match('/^\d{2}[-\/]\d{2}[-\/]\d{4}$/', $keyword)) {
                // Replace slashes with hyphens so it matches 'm-d-Y' format perfectly
                $normalizedKeyword = str_replace('/', '-', $keyword);
                return Carbon::createFromFormat('m-d-Y', $normalizedKeyword)->format('Y-m-d');
            }
            
            // Handles full text layout forms like "Sep 23, 2026" safely
            return Carbon::parse($keyword)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

}
