<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClusterRequest;
use App\Models\Task;
use App\Models\Cluster;
use App\Models\Permission;
use App\Traits\ResponseTraits;
use App\Services\ClustersServices;
use App\Http\Resources\ClusterResource;
use App\Http\Requests\StoreClusterRequest;
use App\Http\Requests\UpdateClusterRequest;

class ClusterController extends Controller
{
    use ResponseTraits;

    public function __construct()
    {
        $this->model = new Cluster();
        $this->service = new ClustersServices();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $result = $this->successResponse('Clusters loaded successfully!');
        try
        {
            $result["data"] =  $this->service->load();
        } catch (\Throwable $th)
        {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);

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
     * @param  \App\Http\Requests\StoreClusterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClusterRequest $request)
    {
        $result = $this->successResponse('Cluster created successfully!');
        try {
            Cluster::create($request->all());
        } catch (\Throwable $th)
        {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->successResponse('Cluster retrieved successfully!');
        try {
            $result["data"] = $this->model::findOrfail($id);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function edit(Cluster $cluster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClusterRequest  $request
     * @param  \App\Models\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function update(ClusterRequest $request, $id)
    {
        $result = $this->successResponse('Cluster updated successfully!');
        try {
            $this->model->findOrfail($id)->update($request->all());

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cluster = Cluster::findOrfail($id);
        $has_related_permission = Permission::where('cluster_id', $cluster->id)->first();
        $has_related_task = Task::where('cluster_id', $cluster->id)->first();

        if($has_related_permission || $has_related_task)
        {
            // return redirect()->back()->withErrors("Cluster cannot be deleted due to existence of related record.");
            $result = $this->failedDeleteValidationResponse('Data cannot be deleted due to existence of related record.');
        }
        else
        {
            $result = $this->successResponse('Clusterser deleted successfully!');
            try {
                $cluster->delete();
            } catch (\Throwable $th)
            {
                return $this->errorResponse($th);
            }
        }

        return $this->returnResponse($result);
    }
}
