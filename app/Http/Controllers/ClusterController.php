<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Cluster;
use App\Models\Permission;
use App\Http\Resources\ClusterResource;
use App\Http\Resources\ClusterCollection;
use App\Http\Requests\StoreClusterRequest;
use App\Http\Requests\UpdateClusterRequest;

class ClusterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $clusters = new ClusterCollection(Cluster::all());
        return view('pages.admin.clusters.list', compact('clusters'));
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
        $cluster = new ClusterResource(Cluster::create($request->all()));
        return redirect()->back()->with('with_success', "Cluster created successfully!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function show(Cluster $cluster)
    {
        // return new ClusterResource($cluster);
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
    public function update(UpdateClusterRequest $request, Cluster $cluster)
    {
        $cluster = $cluster->update($request->all());
        return redirect()->back()->with('with_success', "Cluster updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cluster $cluster)
    {
        $has_related_permission = Permission::where('cluster_id', $cluster['id'])->first();
        $has_related_task = Task::where('cluster_id', $cluster['id'])->first();

        if($has_related_permission || $has_related_task)
        {
            return redirect()->back()->withErrors("Cluster cannot be deleted due to existence of related record.");
        }
        else
        {
            $cluster->delete();
            return redirect()->back()->with('with_success', "Cluster deleted successfully!");
        }
    }
}
