<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\ClientActivity;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ClientActivityResource;
use App\Http\Resources\ClientActivityCollection;
use App\Http\Requests\StoreClientActivityRequest;
use App\Http\Controllers\GlobalVariableController;
use App\Http\Requests\UpdateClientActivityRequest;

class ClientActivityController extends GlobalVariableController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request['user_id'])
        {
            $client_activities = ClientActivity::where('agent_id', $request['user_id'])->get();

            return view('pages.admin.client-activities.user-client-activities', compact('client_activities'));
        }
        else
        {
            // accountant
            if(auth()->user()->isAccountant())
            {
                return view('errors.401');
            }

            $permissions = User::with([
                'theclientactivities:agent_id'
            ])
            ->select('id','fullname','cluster_id','client_id','tl_id','om_id','permission')
            ->where('permission','<>','superadmin');

            // admin
            if(auth()->user()->isAdmin())
            {
                $permissions = $permissions->get();
            }
            // operations manager
            elseif(auth()->user()->isOperationsManager())
            {
                $permissions = $permissions->OMPermission()->get();
            }
            // team leader
            elseif(auth()->user()->isTeamLeader())
            {
                $permissions = $permissions->TLPermission()->get();
            }

            return view('pages.admin.client-activities.list', compact('permissions'));
        }
    }

    public function showActivities()
    {
        $client_activities = ClientActivity::where('agent_id', auth()->user()->id)->get();
        return view('pages.agent.activities.list', compact('client_activities'));
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
     * @param  \App\Http\Requests\StoreClientActivityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientActivityRequest $request)
    {
        $client_activity = ClientActivity::updateOrCreate(
            [
                'agent_id' => $request['agent_id'],
                'name' => $request['name']
            ],
            [
                'frequency' => strtolower($request['frequency']),
                'schedule' => $request['schedule'],
                'function' => $request['function'],
            ]
        );
        return redirect()->back()->with('with_success', "Activity created successfully!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientActivity  $clientActivity
     * @return \Illuminate\Http\Response
     */
    public function show(ClientActivity $clientActivity)
    {
        return new ClientActivityResource($clientActivity);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClientActivity  $clientActivity
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientActivity $clientActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClientActivityRequest  $request
     * @param  \App\Models\ClientActivity  $clientActivity
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientActivityRequest $request, ClientActivity $clientActivity)
    {
        $clientActivity->update($request->all());
        return redirect()->back()->with('with_success', "Activity updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClientActivity  $clientActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientActivity $clientActivity)
    {
        $has_related_task = Task::where('client_activity_id', $clientActivity['id'])->first();

        if($has_related_task)
        {
            return redirect()->back()->withErrors("Activity cannot be deleted due to existence of related record.");
        }
        else
        {
            $clientActivity->delete();
            return redirect()->back()->with('with_success', "Activity deleted successfully!");
        }
    }
}
