<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientControllerAPI extends Controller
{
    // GET ALL CLIENTS
    public function getAllClients(Request $request)
    {
        if($request->ajax())
        {
            $clients = Client::query()
                ->with([
                    'thecluster:id,name'
                ])
                ->orderBy('name','asc');

            $clients = auth()->user()->isAdmin() ? $clients : $clients->cluster();

            return datatables($clients)
                ->editColumn('updated_at', (function($value){
                    return $value->updated_at ? date('d-M-y h:i:s a', strtotime($value->updated_at)) : '-';
                }))
                ->addColumn('action', (function($value){
                    return '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit CLient" onclick=CLIENT.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>
                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" title="Delete Client" onclick=CLIENT.destroy('.$value->id.')><i class="fas fa-times"></i></button>';
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
