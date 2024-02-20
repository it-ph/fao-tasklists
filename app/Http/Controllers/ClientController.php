<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Client;
use App\Models\Permission;
use App\Models\UserClient;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ClientResource;
use App\Http\Resources\ClientCollection;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Controllers\GlobalVariableController;

class ClientController extends GlobalVariableController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if(Auth::user()->isAdmin())
        {
            $clients = new ClientCollection(Client::query()
                ->with('thecluster')
                ->get());
        }
        else
        {
            $clients = new ClientCollection(Client::query()
                ->with('thecluster')
                ->cluster()
                ->get());
        }

        return view('pages.admin.clients.list', compact('clients'));
    }

    public function getClients($cluster_id)
    {
        $clients = Client::query()
            ->with('thecluster');

        if(Auth::user()->isAdmin())
        {
            $clients = $clients->get();
        }
        else
        {
            $clients = $clients->where('cluster_id', $cluster_id)->get();
        }

        return $clients;
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
     * @param  \App\Http\Requests\StoreClientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        $client = new ClientResource(Client::create($request->all()));
        return redirect()->back()->with('with_success', "Client created successfully!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        // return new ClientResource($client);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClientRequest  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client = $client->update($request->all());
        return redirect()->back()->with('with_success', "Client updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $has_related_task = Task::where('client_id', $client['id'])->first();
        $has_user_client = UserClient::where('client_id', $client['id'])->first();
        $has_related_permission = Permission::where('client_id', $client['id'])->first();

        if($has_related_task || $has_user_client || $has_related_permission)
        {
            return redirect()->back()->withErrors("Client cannot be deleted due to existence of related record.");
        }
        else
        {
            $client->delete();
            return redirect()->back()->with('with_success', "Client deleted successfully!");
        }
    }
}
