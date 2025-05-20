<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ChangeRequest;
use App\Traits\ResponseTraits;
use App\Http\Requests\StoreUpdateChangeRequest;

class ChangeRequestController extends Controller
{
    use ResponseTraits;

    public function __construct()
    {
        $this->model = new ChangeRequest();
    }

    public function store(StoreUpdateChangeRequest $request)
    {
        $result = $this->successResponse('Cluster created successfully!');
        try {
            $request['created_by'] = auth()->user()->id;
            $request['cluster_id'] = auth()->user()->cluster_id;
            ChangeRequest::create($request->all());
        } catch (\Throwable $th)
        {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function show($id)
    {
        $result = $this->successResponse('Change Request retrieved successfully!');
        try {
            $result["data"] = $this->model::query()
                ->where('id', $id)
                ->first();
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function update(StoreUpdateChangeRequest $request, $id)
    {
        $result = $this->successResponse('Change Request updated successfully!');
        try {
            $this->model->findOrfail($id)->update($request->all());

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function close(Request $request, $id)
    {
        $request['status'] = 'Closed';
        $result = $this->successResponse("Change Request has been ".$request['status']." successfully!");
        try {
            $change_request = $this->model->findOrfail($id);
            $status = $request['status'];
            $remarks = $request['remarks'] ?? null;  // Default to null if 'remarks' is not set
            $closed_at = Carbon::now();
            $changed_by = auth()->user()->id;

            // Prepare the update data
            $updateData = [
                'status' => $status,
                'closed_at' => $closed_at,
                'changed_by' => $changed_by,
            ];

            // Only update 'remarks' if it's not empty
            if ($remarks) {
                $updateData['remarks'] = $remarks;
            }

            // Perform the update
            $change_request->update($updateData);

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function count()
    {
        $change_requests = ChangeRequest::query()
            ->where('status','Open');

        $count = auth()->user()->isAdmin()
            ? $change_requests->count()
            : $change_requests->OMPermission()->count();

        // Get user permission
        $userPermission = auth()->user()->permission;

        // Filter tasks based on user permission
        switch ($userPermission) {
            case 'superadmin':
            case 'admin':
                $count = $change_requests->count();
                break;
            case 'operations manager':
                $count = $change_requests->OMPermission()->count();
                break;
            case 'team leader':
                $count = $change_requests->TLPermission()->count();
                break;
            case 'accountant':
                $count = $change_requests->AccountantPermission()->count();
                break;
            default:
                break;
        }

        return response()->json(['count' => $count]);
    }
}
