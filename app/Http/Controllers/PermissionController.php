<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Permission;
use App\Models\UserProfile;
use App\Models\ClientActivity;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\PermissionCollection;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Controllers\GlobalVariableController;

class PermissionController extends GlobalVariableController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::with([
            'theuser:id,email',
            'theuser.employeeprofile:emp_id,emp_code,fullname,last_name',
            'thecluster:id,name',
            'theclient:id,name',
            'thetl.theuser','thetl.theuser.employeeprofile',
            'theom.theuser','theom.theuser.employeeprofile',
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

        return view('pages.admin.permissions.list',compact('permissions'));
    }

    public function getTLOMs($cluster_id)
    {
        $hr_portal = (new UserProfile())->getConnection()->getDatabaseName();
        $permissions = Permission::query()
                ->from('permissions as ftp')
                ->leftjoin($hr_portal.'.hr_employee_profile as hr','ftp.user_id', '=', 'hr.emp_id')
                ->select(['ftp.id','ftp.user_id','ftp.cluster_id','ftp.permission','hr.fullname','hr.last_name','hr.emp_id','hr.emp_code'])
                ->where('ftp.cluster_id',$cluster_id)
                ->whereIn('ftp.permission',['admin','team leader','operations manager'])
                ->orderBy('hr.fullname')
                ->get();

        return $permissions;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePermissionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermissionRequest $request)
    {
        $permission = new PermissionResource(Permission::create($request->all()));
        return redirect()->back()->with('with_success', "User created successfully!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        // return new PermissionResource($permission->loadMissing(['theuser','theuser.employeeprofile','thecluster','thetl.theuser.employeeprofile','theom.theuser.employeeprofile']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePermissionRequest  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission = $permission->update($request->all());
        return redirect()->back()->with('with_success', "User updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $has_related_permission = Permission::where('tl_id', $permission['user_id'])->orwhere('om_id', $permission['user_id'])->first();
        $has_related_task = Task::where('agent_id', $permission['user_id'])->first();
        $has_related_client_activity = ClientActivity::where('agent_id', $permission['user_id'])->first();

        if($has_related_permission || $has_related_task || $has_related_client_activity)
        {
            return redirect()->back()->withErrors("User cannot be deleted due to existence of related record.");
        }
        else
        {
            $permission->delete();
            return redirect()->back()->with('with_success', "User deleted successfully!");
        }
    }
}
