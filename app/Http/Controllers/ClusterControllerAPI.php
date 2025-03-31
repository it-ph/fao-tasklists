<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use Illuminate\Http\Request;

class ClusterControllerAPI extends Controller
{
    // GET ALL CLUSTERS
    public function getAllClusters(Request $request)
    {
        if($request->ajax())
        {
            $clusters = Cluster::orderBy('name','asc');

            return datatables($clusters)
                ->editColumn('updated_at', (function($value){
                    return $value->updated_at ? date('d-M-y h:i:s a', strtotime($value->updated_at)) : '-';
                }))
                ->addColumn('action', (function($value){
                    return '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Cluster" onclick=CLUSTER.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>
                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" title="Delete Cluster" onclick=CLUSTER.destroy('.$value->id.')><i class="fas fa-times"></i></button>';
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
