<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Permission;
use App\Models\UserProfile;
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
        $response = Http::get($hrportal.'/api/HREmployeeProfileAPI/eyJ0eXAiOiJKV1QiLCJub25jZSI6InlVTmhITXhtYnNkemdKdXBRTFZLV3c3RGprNUc4eW5uRzFUM2lrMzZPTE0iLCJhbGciOiJSUzI1Ni/IsIng1dCI6Ii1LSTNROW5OUjdiUm9meG1lWm9YcWJIWkdldyIsImtpZCI6Ii1LSTNROW5OUjdiUm9meG1lWm9YcWJIWkdldyJ9.eyJhdWQiOiIwMDAwMDAwMy0wMDAwLTAwMDAtYzAwMC0wMDAwMDAwMDAwMDAiLCJpc3MiOiJodHRwczovL3N0cy53aW5kb3dzLm5ldC80YTgxMTQ1OC0wZmRjLTQ3NjgtYWNlYy0xMTgyYjgwOTE3ZWUvIiwiaWF0IjoxNjgxNDU2NjEwLCJuYmYiOjE2ODE0NTY2MTAsImV4cCI6MTY4MTQ2MTI1NCwiYWNjdCI6MCwiYWNyIjoiMSIsImFpbyI6IkFWUUFxLzhUQUFBQVRmaEkwNW1Lc3lwQ2FhbGRDMVd0dUFyWmFvV2t2Rkp6bHJmRWJSUFlOUitjcGJkSnYrODhBeG9VYjh0UkJRTFl6YlZQeHRteFNSRFc0RGZEUGRZQ1Y0VVhieSt6NVU1UHFQQml1SklFQ1NvPSIsImFtciI6WyJwd2QiLCJtZmEiXSwiYXBwX2Rpc3BsYXluYW1lIjoiUGVyc29uaXYgU2lnbiIsImFwcGlkIjoiODg1MDFjNWYtYWJhNS00NDlkLWFjNTktODA4YTMzZmU3OThhIiwiYXBwaWRhY3IiOiIxIiwiZmFtaWx5X25hbWUiOiJCdWd0b25nIiwiZ2l2ZW5fbmFtZSI6IlJpY28iLCJpZHR5cCI6InVzZXIiLCJpcGFkZHIiOiIxODAuMTk1LjE5Ny4xMDIiLCJuYW1lIjoiUmljbyBHLiBCdWd0b25nIiwib2lkIjoiZGJjM2Q4MWItN2Q5MS00NzNhLTkxZjYtMTIxN/zgyNTJjOTQ3Iiwib25wcmVtX3NpZCI6IlMtMS01LTIxLTM5NTMzODM4MzYtMTk1Njg1MTMwNS0xODk3NDk5MTE4LTczMTciLCJwbGF0ZiI6IjMiLCJwdWlkIjoiMTAwMzIwMDE0QkIxRDdDNyIsInJoIjoiMC5BVW9BV0JTQlN0d1BhRWVzN0JHQ3VBa1g3Z01BQUFBQUFBQUF3QUFBQUFBQUFBQktBS3cuIiwic2NwIjoiQ2FsZW5kYXJzLlJlYWRXcml0ZSBDb250YWN0cy5SZWFkV3JpdGUgRmlsZXMuUmVhZFdyaXRlIE1haWwuUmVhZFdyaXRlIE1haWwuU2VuZCBNYWlsYm94U2V0dGluZ3MuUmVhZFdyaXRlIG9wZW5pZCBUYXNrcy5SZWFkV3JpdGUgVXNlci5SZWFkV3JpdGUgcHJvZmlsZSBlbWFpbCIsInN1YiI6Iklnb3QwTm42a2xhSVFjNVBCVWN3d1BGakxkZzFjVjhGa1NXMlhzWUd4WFEiLCJ0ZW5hbnRfcmVnaW9uX3Njb3BlIjoiQVMiLCJ0aWQiOiI0YTgxMTQ1OC0wZmRjLTQ3NjgtYWNlYy0xMTgyYjgwOTE3ZWUiLCJ1bmlxdWVfbmFtZSI6InJpY28uYnVndG9uZ0BQZXJzb25pdi5jb20iLCJ1cG4iOiJyaWNvLmJ1Z3RvbmdAUGVyc29uaXYuY29tIiwidXRpIjoiUzlPT3VtNDF4VUNaX1VZVldRWWRBQSIsInZlciI6IjEuMCIsIndpZHMiOlsiYjc5ZmJmNGQtM2VmOS00Njg5LTgxNDMtNzZiMTk0ZTg1NTA5Il0sInhtc19zdCI6eyJzdWIiOiJSRHFpcC1Rb2ItSXBfWlgzd20tWmN4Y2dNVjdRZ2lpcjJHOXc1NGhrNGI4In0sInhtc190Y2R0IjoxNTU1NDg0Njc0fQ.b7mtsVpgXOVTu3ZKvEL1kQYSHRxeIIT_p3M4B4CgZ6I6ullXMR6CGUKYL0a9cWlirPzNp1GrFeBvGckufZSlJZYDSAD_9BHYNJ0M4Z6X-rx8FtLy2AbuoPlcMqRdEkaQ0tsX_gxkX3RNVlhkWI0S4h4-SDpjy4RBKypuIiVO4QZ7ExaQPUhxVRVtIxsy6s5hATkiKhTWher4SmJMcxCJAFiczpxe0nLuJpgcVFk0sPtaY1aOkaosRPi1Ix6Kb87t5EoVJY9tu4Nochgz5baHszxk50AacbrADKhFDeme_Py3lBgiPrSovu6ApMSjxRhymSu6q4g3-0uRFbEfAIBOuw');
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
        
        return redirect()->back()->with('with_success', "HR Portal Employees has been synchronize!");
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
}
