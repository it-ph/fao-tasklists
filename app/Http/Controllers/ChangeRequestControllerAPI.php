<?php

namespace App\Http\Controllers;

use App\Models\ChangeRequest;
use Illuminate\Http\Request;

class ChangeRequestControllerAPI extends Controller
{
    // GET ALL CHANGE REQUESTS
    public function getAllChangeRequests(Request $request)
    {
        if($request->ajax())
        {
            $change_requests = ChangeRequest::query()
                ->with([
                    'thecreatedby:id,fullname',
                    'thecluster:id,name',
                    'thechangedby:id,fullname',
                ])
                ->orderBy('status','desc')
                ->orderBy('created_at','desc');

                // Get user permission
                $userPermission = auth()->user()->permission;

                // Filter tasks based on user permission
                switch ($userPermission) {
                    case 'superadmin':
                    case 'admin':
                        $change_requests = $change_requests;
                        break;
                    case 'operations manager':
                        $change_requests = $change_requests->OMPermission();
                        break;
                    case 'team leader':
                        $change_requests = $change_requests->TLPermission();
                        break;
                    case 'accountant':
                        $change_requests = $change_requests->AccountantPermission();
                        break;
                    default:
                        break;
                }

            // OPTIMIZATION: Pre-calculate current user ID and permission check once outside row loop
            $currentUser = auth()->user();
            $currentUserId = $currentUser->id;
            $canManage = $currentUser->isOperationsManagerOrAdmin() || $currentUser->isTeamLeaderOrAdmin();

            return datatables($change_requests)
                ->editColumn('task_id', function ($value) {
                    if ($value->task_type === 'task_assignments') {
                        return 'TA' . $value->task_id;
                    }
                    return $value->task_id;
                })
                ->editColumn('created_at', (function($value){
                    return $value->created_at ? date('d-M-y h:i:s a', strtotime($value->created_at)) : '';
                }))
                ->editColumn('changed_by', function ($value) {
                    return $value->thechangedby ? $value->thechangedby->fullname : '';
                })
                ->editColumn('closed_at', (function($value){
                    return $value->closed_at ? date('d-M-y h:i:s a', strtotime($value->closed_at)) : '';
                }))
                ->editColumn('status', (function($value){
                    $statusClass = ($value->status === 'Open') ? 'text-danger' : 'text-primary';
                    return '<span class="' . $statusClass . '"><strong>' . $value->status . '</strong></span>';
                }))
                ->addColumn('action', (function($value) use ($currentUserId, $canManage){
                    // [ORIGINAL UNOPTIMIZED CODE COMMENTED FOR REFERENCE]:
                    // $action = auth()->user()->id == $value->created_by
                    //     ? '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Change Request" onclick=CHANGEREQUEST.edit(' . $value->id . ')><i class="fas fa-pencil-alt"></i></button>'
                    //     : '';
                    // switch ($value->status) {
                    //     case 'Open':
                    //         $action .= auth()->user()->isOperationsManagerOrAdmin() || auth()->user()->isTeamLeaderOrAdmin()
                    //         ? ' <button type="button" class="btn btn-info btn-sm waves-effect waves-light" title="View Change Request" onclick=CHANGEREQUEST.show(' . $value->id . ') id="btn-view-' . $value->id . '"><i class="fas fa-eye"></i></button>
                    //             <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" title="Mark as Closed" onclick=CHANGEREQUEST.close('.$value->id.') id="btn-close-'.$value->id.'"><i class="fas fa-check"></i></button>'
                    //         : '';
                    //         break;
                    //     case 'Closed':
                    //         $action = '-';
                    //         break;
                    //     default:
                    //         break;
                    // }

                    // [OPTIMIZED]: Pre-evaluated variables used
                    $action = $currentUserId == $value->created_by
                        ? '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Change Request" onclick=CHANGEREQUEST.edit(' . $value->id . ')><i class="fas fa-pencil-alt"></i></button>'
                        : '';
                    switch ($value->status) {
                        case 'Open':
                            $action .= $canManage
                            ? ' <button type="button" class="btn btn-info btn-sm waves-effect waves-light" title="View Change Request" onclick=CHANGEREQUEST.show(' . $value->id . ') id="btn-view-' . $value->id . '"><i class="fas fa-eye"></i></button>
                                <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" title="Mark as Closed" onclick=CHANGEREQUEST.close('.$value->id.') id="btn-close-'.$value->id.'"><i class="fas fa-check"></i></button>'
                            : '';
                            break;
                        case 'Closed':
                            $action = '-';
                            break;
                        default:
                            break;
                    }
                    return $action;
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
