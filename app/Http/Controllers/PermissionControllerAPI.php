<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionControllerAPI extends Controller
{
    // GET ALL USERS
    public function getAllUsers(Request $request)
    {
        if($request->ajax())
        {
            $permissions = Permission::with([
                'theuser:emp_id,email,fullname,last_name,employment_status',
                'thecluster:id,name',
                'theclient:id,name',
                'thetl:user_id',
                'thetl.theuser:emp_id,email',
                'thetl.theuser:emp_id,fullname,last_name',
                'theom:user_id',
                'theom.theuser:emp_id,email',
                'theom.theuser:emp_id,fullname,last_name',
            ])
            ->select('id','user_id','cluster_id','client_id','tl_id','om_id','permission')
            ->where('permission','<>','superadmin');

            // admin
            if(auth()->user()->isAdmin())
            {
                $permissions = $permissions->get();
            }
            // operations manager
            elseif(auth()->user()->isOperationsManager())
            {
                $permissions = $permissions->OMPermission()->get();
            }
            // team leader
            elseif(auth()->user()->isTeamLeader())
            {
                $permissions = $permissions->TLPermission()->get();
            }

            return datatables($permissions)
                ->addColumn('thecluster', function ($value) {
                    return $value->thecluster ? $value->thecluster->name : "";
                })
                ->addColumn('theclient', function ($value) {
                    return $value->theclient ? $value->theclient->name : "";
                })
                ->addColumn('full_name', function ($value) {
                    return $value->full_name;
                })
                ->addColumn('tl_full_name', function ($value) {
                    return $value->tl_full_name;
                })
                ->addColumn('om_full_name', function ($value) {
                    return $value->om_full_name;
                })
                ->addColumn('permission', function ($value) {
                    return ucfirst($value->permission);
                })
                ->addColumn('employment_status', function ($value) {
                    return $value->theuser->employment_status == 'active' ? '<span class="text-success"><strong>Active</strong></span>' : '<label class="text-danger"><strong>Inactive</strong></label>';
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
}
