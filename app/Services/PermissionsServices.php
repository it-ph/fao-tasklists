<?php

namespace App\Services;

use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class PermissionsServices
{
    public function load()
    {
        $datastorage = [];
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
        if(Auth::user()->isAdmin())
        {
            $permissions = $permissions->get();
        }
        // operations manager
        elseif(Auth::user()->isOperationsManager())
        {
            $permissions = $permissions->OMPermission()->get();
        }
        // team leader
        elseif(Auth::user()->isTeamLeader())
        {
            $permissions = $permissions->TLPermission()->get();
        }

        foreach($permissions as $value) {
            $employee_name = $value->theuser ? $value->theuser->fullname.' '.$value->theuser->last_name : "";
            $email_address = $value->theuser ? strtolower($value->theuser->email) : "";
            $cluster = $value->thecluster ? $value->thecluster->name : "";
            $client = $value->theclient ? $value->theclient->name : "";
            $team_leader = $value->thetl ? $value->thetl->theuser->fullname.' '.$value->thetl->theuser->last_name : "";
            $operations_manager = $value->theom ? $value->theom->theuser->fullname.' '.$value->theom->theuser->last_name : "";
            $permission = ucwords($value->permission);
            $employment_status = $value->theuser->employment_status == 'active' ? '<span class="text-success"><strong>Active</strong></span>' : '<label class="text-danger"><strong>Inactive</strong></label>';
            $action ='<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit User" onclick=PERMISSION.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>';
                    // <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" title="Delete User" onclick=PERMISSION.destroy('.$value->id.')><i class="fas fa-times"></i></button>';

            $datastorage[] = [
                'id' => $value->id,
                'employee_name' => $employee_name,
                'email_address' => $email_address,
                'cluster' => $cluster,
                'client' => $client,
                'team_leader' => $team_leader,
                'operations_manager' => $operations_manager,
                'permission' => $permission,
                'employment_status' => $employment_status,
                'action' => $action,
            ];
        }

        return $datastorage;
    }
}
