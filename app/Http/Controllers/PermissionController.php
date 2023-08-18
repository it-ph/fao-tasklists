<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Permission;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\PermissionCollection;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Controllers\GlobalVariableController;
use App\Models\ClientActivity;

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
        return view('pages.admin.permissions.list');
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
