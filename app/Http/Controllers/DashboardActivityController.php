<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\DashboardActivity;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\DashboardActivityResource;
use App\Http\Controllers\GlobalVariableController;
use App\Http\Resources\DashboardActivityCollection;
use App\Http\Requests\StoreDashboardActivityRequest;
use App\Http\Requests\UpdateDashboardActivityRequest;

class DashboardActivityController extends GlobalVariableController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if(Auth::user()->isAdmin())
        {
            $dashboard_activities = new DashboardActivityCollection(DashboardActivity::query()
                ->with('thecluster')
                ->get());
        }
        else
        {
            $dashboard_activities = new DashboardActivityCollection(DashboardActivity::query()
                ->with('thecluster')
                ->cluster()
                ->get());
        }


        return view('pages.admin.dashboard-activities.list', compact('dashboard_activities'));
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
     * @param  \App\Http\Requests\StoreDashboardActivityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDashboardActivityRequest $request)
    {
        $dashboard_activity = new DashboardActivityResource(DashboardActivity::create($request->all()));
        return redirect()->back()->with('with_success', "Dashboard Activity created successfully!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DashboardActivity  $dashboardActivity
     * @return \Illuminate\Http\Response
     */
    public function show(DashboardActivity $dashboardActivity)
    {
        // return new DashboardActivityResource($dashboardActivity);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DashboardActivity  $dashboardActivity
     * @return \Illuminate\Http\Response
     */
    public function edit(DashboardActivity $dashboardActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDashboardActivityRequest  $request
     * @param  \App\Models\DashboardActivity  $dashboardActivity
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDashboardActivityRequest $request, DashboardActivity $dashboardActivity)
    {
        $dashboardActivity->update($request->all());
        return redirect()->back()->with('with_success', "Dashboard Activity updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DashboardActivity  $dashboardActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(DashboardActivity $dashboardActivity)
    {
        $has_related_task = Task::where('dashboard_activity_id', $dashboardActivity['id'])->first();

        if($has_related_task)
        {
            return redirect()->back()->withErrors("Dashboard Activity cannot be deleted due to existence of related record.");
        }
        else
        {
            $dashboardActivity->delete();
            return redirect()->back()->with('with_success', "Dashboard Activity deleted successfully!");
        }
    }
}
