<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ResponseTraits;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\GlobalVariableController;

class UserController extends GlobalVariableController
{
    use ResponseTraits;

    public function __construct()
    {
        parent::__construct();
    }

    public function getTLOMs($cluster_id)
    {
        $users = User::query()
            ->select(['id','id','fullname','permission','status'])
            ->whereIn('permission',['admin','team leader','operations manager'])
            ->orderBy('fullname')
            ->get();

        return $users;
    }

    public function getAccountants($user_id)
    {
        $users = User::query()
                ->select(['id','id','fullname','permission','status'])
                ->where('permission','<>','superadmin')
                ->orderBy('fullname')
                ->get();

        if(auth()->user()->isAdmin())
        {
            $users = $users->get();
        }
        // operations manager
        elseif(auth()->user()->isOperationsManager())
        {
            $users = $users->OMPermission()->get();
        }
        // team leader
        elseif(auth()->user()->isTeamLeader())
        {
            $users = $users->TLPermission()->get();
        }

        return $users;
    }

    public function store(StoreUserRequest $request)
    {
        $result = $this->successResponse('User created successfully!');
        try {
            User::create($request->all());
        } catch (\Throwable $th)
        {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function show($id)
    {
        $result = $this->successResponse('User retrieved successfully!');
        try
        {
            $result["data"] = User::findOrfail($id);
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $result = $this->successResponse('User updated successfully!');
        try {
            User::findOrfail($id)->update($request->all());
        } catch (\Throwable $th)
        {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function updateShiftDate(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();
        $user->update(
            [
                'shift_date' => $request['shift_date'].' 00:00:00'
            ]
        );
        return redirect()->back()->with('with_success', "Default Shift Date updated successfully!");
    }
}
