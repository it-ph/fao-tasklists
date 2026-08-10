<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;

class UserControllerAPI extends Controller
{
    // GET ALL USERS
    public function getAllUsers(Request $request)
    {
        if($request->ajax())
        {
            $permissions = User::with([
                'thecluster:id,name',
                'theclient:id,name',
                'thetl:id,fullname',
                'theom:id,fullname',
            ])
            ->select('id','email','fullname','cluster_id','client_id','tl_id','om_id','permission','status')
            ->where('permission','<>','superadmin');

            // admin
            if(auth()->user()->isAdmin())
            {
                $permissions = $permissions;
            }
            // operations manager
            elseif(auth()->user()->isOperationsManager())
            {
                $permissions = $permissions->OMPermission();
            }
            // team leader
            elseif(auth()->user()->isTeamLeader())
            {
                $permissions = $permissions->TLPermission();
            }

            return datatables($permissions)
                ->addColumn('thetl', function ($value) {
                    return $value->thetl ? $value->thetl->fullname : "";
                })
                ->addColumn('theom', function ($value) {
                    return $value->theom ? $value->theom->fullname : "";
                })
                ->editColumn('cluster_id', function ($value) {
                    return $value->thecluster ? $value->thecluster->name : "";
                })
                ->editColumn('client_id', function ($value) {
                    return $value->theclient ? $value->theclient->name : "";
                })
                ->addColumn('permission', function ($value) {
                    return ucwords($value->permission);
                })
                ->addColumn('status', function ($value) {
                    return $value->status == 'active' ? '<span class="text-success"><strong>Active</strong></span>' : '<label class="text-danger"><strong>Inactive</strong></label>';
                })
                ->addColumn('action', (function($value){
                    return '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit User" onclick=PERMISSION.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>';
                }))
                ->rawColumns(
                [
                    'action',
                ])
                ->escapeColumns([])
                ->make(true);
        }
    }

    // GET LIVE USER STATUS DATA
    public function getLiveUserStatus(Request $request)
    {
        if ($request->ajax()) {
            // 1. Core query mapping with structural relationships and today's attendance logs
            $query = User::with([
                // 'thecluster:id,name',
                // 'theclient:id,name',
                // 'thetl:id,fullname',
                // Pre-load ONLY the absolute latest single record row from today to resolve accurate live status
                // 'todaysAttendance' => function ($subQuery) {
                //     $subQuery->whereDate('shift_date', \Carbon\Carbon::today())
                //             ->latest('id');
                // }

                'theattendances' => function ($subQuery) {
                    $subQuery->where(function($q) {
                        // Scenario A: Clocked in today
                        $q->whereDate('shift_date', \Carbon\Carbon::today());
                    })
                    ->orWhere(function($q) {
                        // Scenario B: Night shift (Clocked in yesterday, still working)
                        $q->whereDate('shift_date', \Carbon\Carbon::yesterday())
                        ->whereNull('clock_out');
                    })
                    ->latest('id');
                }

            ])
            ->select('id', 'fullname', 'permission')
            ->where('permission', '<>', 'superadmin');

            // admin
            if (auth()->user()->isAdmin())
            {
                $query = $query;
            }
            // operations manager
            elseif (auth()->user()->isOperationsManager())
            {
                $query = $query->OMPermission();
            }
            // team leader
            elseif (auth()->user()->isTeamLeader())
            {
                $query = $query->TLPermission();
            }

            return datatables($query)
                ->addColumn('clock_in', function ($user) {
                    $log = $user->theattendances->first();
                    return ($log && $log->clock_in)
                        ? \Carbon\Carbon::parse($log->clock_in)->format('h:i A')
                        : '<span class="text-muted">—</span>';
                })
                ->addColumn('clock_out', function ($user) {
                    $log = $user->theattendances->first();
                    return ($log && $log->clock_out)
                        ? \Carbon\Carbon::parse($log->clock_out)->format('h:i A')
                        : '<span class="text-muted">—</span>';
                })
                ->addColumn('live_status', function ($user) {
                    $log = $user->theattendances->first();

                    if (!$log) {
                        return '<span class="badge bg-danger rounded-pill px-2.5 py-1.5 text-uppercase fw-bold">Absent</span>';
                    }

                    if ($log->clock_in && !$log->clock_out) {
                        return '<span class="badge bg-success rounded-pill px-2.5 py-1.5 text-uppercase fw-bold">Clocked-In</span>';
                    }

                    return '<span class="badge bg-secondary rounded-pill px-2.5 py-1.5 text-uppercase fw-bold">Clocked-Out</span>';
                })
                ->addColumn('work_status', function ($user) {
                    $activeTask = Task::where('agent_id', $user->id)->where('status', 'In Progress')->first(['id']);
                    if ($activeTask) {
                        return '<span class="text-primary fw-bold">' . $activeTask->id . '</span>';
                    }

                    $activeAssignment = TaskAssignment::where('agent_id', $user->id)->where('status', 'In Progress')->first(['id']);
                    if ($activeAssignment) {
                        return '<span class="text-success fw-bold">TA' . $activeAssignment->id . '</span>';
                    }

                    return '<span class="text-muted fw-semibold">—</span>';
                })

                ->rawColumns([
                    'clock_in',
                    'clock_out',
                    'live_status',
                    'work_status',
                ])
                ->escapeColumns([])
                ->make(true);
        }
    }
}
