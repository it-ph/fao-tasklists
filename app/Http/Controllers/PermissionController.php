<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Permission;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\ClientActivity;
use App\Traits\ResponseTraits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Services\PermissionsServices;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\PermissionCollection;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Controllers\GlobalVariableController;

class PermissionController extends GlobalVariableController
{
    use ResponseTraits;

    public function __construct()
    {
        parent::__construct();
        $this->service = new PermissionsServices();
    }

    public function hrportalusers()
    {
        $hrportal = env('HRPORTAL_URL');
        $response = Http::withOptions(['verify' => false])->get($hrportal.'/api/HREmployeeProfileAPI/eyJ0eXAiOiJKV1QiLCJub25jZSI6InlVTmhITXhtYnNkemdKdXBRTFZLV3c3RGprNUc4eW5uRzFUM2lrMzZPTE0iLC');
        $jsonData = $response->json();
        $users = json_encode($jsonData);
        $hrportal_users = json_decode($users);

        foreach($hrportal_users as $data)
        {
            User::updateOrcreate(
            [
                'email' => $data->email,
            ],
            [
                'emp_id' => $data->emp_id,
                'email' => $data->email,
                'emp_code' => $data->emp_code,
                'fullname' => $data->fullname,
                'last_name' => $data->last_name,
                'position' => $data->position,
                'date_hired' => $data->date_hired,
                'employment_status' => $data->employment_status,
                'password' => null
            ]);
        }

        return redirect()->back()->with('with_success', "HR Portal Employees has been synchronized!");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->successResponse('Users loaded successfully!');
        try
        {
            $result["data"] =  $this->service->load();
        } catch (\Throwable $th)
        {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function getTLOMs($cluster_id)
    {
        $permissions = Permission::query()
                ->from('permissions as ftp')
                ->leftjoin('users as hr','ftp.user_id', '=', 'hr.emp_id')
                ->select(['ftp.id','ftp.user_id','ftp.cluster_id','ftp.permission','hr.fullname','hr.last_name','hr.emp_id'])
                ->where('ftp.cluster_id',$cluster_id)
                ->whereIn('ftp.permission',['admin','team leader','operations manager'])
                ->orderBy('hr.fullname')
                ->get();

        return $permissions;
    }

    public function getAccountants($user_id)
    {
        $permissions = Permission::query()
                ->from('permissions as ftp')
                ->leftjoin('users as hr','ftp.user_id', '=', 'hr.emp_id')
                ->select(['ftp.id','ftp.user_id','ftp.permission','hr.fullname','hr.last_name','hr.emp_id'])
                ->where('ftp.permission','<>','superadmin')
                ->orderBy('hr.fullname');

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
        $result = $this->successResponse('User created successfully!');
        try {
            Permission::create($request->all());
        } catch (\Throwable $th)
        {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->successResponse('User retrieved successfully!');
        try
        {
            $result["data"] = Permission::findOrfail($id);
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
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
    public function update(UpdatePermissionRequest $request, $id)
    {
        $result = $this->successResponse('User updated successfully!');
        try {
            Permission::findOrfail($id)->update($request->all());
        } catch (\Throwable $th)
        {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrfail($id);
        $has_related_permission = Permission::where('tl_id', $permission->user_id)->orwhere('om_id', $permission->user_id)->first();
        $has_related_task = Task::where('agent_id', $permission->user_id)->first();
        $has_related_client_activity = ClientActivity::where('agent_id', $permission->user_id)->first();

        if($has_related_permission || $has_related_task || $has_related_client_activity)
        {
            $result = $this->failedDeleteValidationResponse('Data cannot be deleted due to existence of related record.');
        }
        else
        {
            $result = $this->successResponse('User deleted successfully!');
            try {
                $permission->delete();
            } catch (\Throwable $th)
            {
                return $this->errorResponse($th);
            }
        }

        return $this->returnResponse($result);
    }

    public function updateShiftDate(Request $request)
    {
        $permission = Permission::where('user_id', Auth::user()->emp_id)->first();
        $permission->update(
            [
                'shift_date' => $request['shift_date'].'00:00:00'
            ]
        );
        return redirect()->back()->with('with_success', "Default Shift Date updated successfully!");
    }
}
