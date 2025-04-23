<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            ->select('id','id','email','fullname','cluster_id','client_id','tl_id','om_id','permission','status')
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
                ->addColumn('thecluster', function ($value) {
                    return $value->thecluster ? $value->thecluster->name : "";
                })
                ->addColumn('theclient', function ($value) {
                    return $value->theclient ? $value->theclient->name : "";
                })
                ->addColumn('permission', function ($value) {
                    return ucfirst($value->permission);
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
}
