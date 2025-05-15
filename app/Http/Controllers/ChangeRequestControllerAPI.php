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
                    case 'accountant':
                        $change_requests = $change_requests->UserPermission();
                        break;
                    default:
                        break;
                }

            return datatables($change_requests)
                ->editColumn('changed_by', function ($value) {
                    return $value->thechangedby ? $value->thechangedby->fullname : '';
                })
                ->editColumn('closed_at', (function($value){
                    return $value->closed_at ? date('d-M-y h:i:s a', strtotime($value->closed_at)) : '';
                }))
                ->editColumn('status', (function($value){
                    $statusClass = '';
                    switch ($value->status) {
                        case 'Open':
                            $statusClass = 'text-danger';
                            break;
                        case 'Closed':
                            $statusClass = 'text-primary';
                            break;
                        default:
                            break;
                    }

                    $status = '<span class="' . $statusClass . '"><strong>' . $value->status . '</strong></span>';
                    return $status;
                }))
                ->addColumn('action', (function($value){
                    $action = auth()->user()->id == $value->created_by
                        ? '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Change Request" onclick=CHANGEREQUEST.edit(' . $value->id . ')><i class="fas fa-pencil-alt"></i></button>'
                        : '';
                    switch ($value->status) {
                        // case 'Open':
                        //     $action .= ' <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" title="View Change Request" onclick=CHANGEREQUEST.show(' . $value->id . ') id="btn-view-' . $value->id . '"><i class="fas fa-eye"></i></button>
                        //         <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" title="Mark as Closed" onclick=CHANGEREQUEST.close(' . $value->id . ') id="btn-close-' . $value->id . '"><i class="fas fa-check"></i></button>';
                        //     break;
                        // case 'Closed':
                        //     $action .= '<button type="button" class="btn btn-primary btn-sm waves-effect waves-light" title="View Change Request" onclick=CHANGEREQUEST.show(' . $value->id . ') id="btn-view-' . $value->id . '"><i class="fas fa-eye"></i></button>';
                        //     break;
                        case 'Open':
                            $action .= ' <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" title="Mark as Closed" onclick=CHANGEREQUEST.close('.$value->id.') id="btn-close-'.$value->id.'"><i class="fas fa-check"></i></button>';
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
