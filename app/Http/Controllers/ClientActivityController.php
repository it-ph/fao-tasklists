<?php

namespace App\Http\Controllers;

use App\Models\Task;
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
            $client_activities = new ClientActivityCollection(ClientActivity::where('agent_id', $request['user_id'])->get());
            return view('pages.admin.client-activities.user-client-activities', compact('client_activities'));
        }
        else
        {
            if(Auth::user()->isAccountant())
            {
                return view('errors.401');
            }
            return view('pages.admin.client-activities.list');
        }
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
        $client_activity = new ClientActivityResource(ClientActivity::updateOrCreate(
            [
                'agent_id' => $request['agent_id'],
                'name' => $request['name']
            ]
        ));
        return redirect()->back()->with('with_success', "Client Activity created successfully!");
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
        return redirect()->back()->with('with_success', "Client Activity updated successfully!");
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
            return redirect()->back()->withErrors("Client Activity cannot be deleted due to existence of related record.");
        }
        else
        {
            $clientActivity->delete();
            return redirect()->back()->with('with_success', "Client Activity deleted successfully!");
        }
    }
}
