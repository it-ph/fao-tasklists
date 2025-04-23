<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Client;
use App\Traits\ResponseTraits;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ClientCollection;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Controllers\GlobalVariableController;

class ClientController extends GlobalVariableController
{
    use ResponseTraits;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Client();
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
        // $client = new ClientResource(Client::create($request->all()));
        // return redirect()->back()->with('with_success', "Client created successfully!");

        $result = $this->successResponse('Client created successfully!');
        try {
            Client::create($request->all());
        } catch (\Throwable $th)
        {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->successResponse('Client retrieved successfully!');
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
    public function update(UpdateClientRequest $request, $id)
    {
        $result = $this->successResponse('Client updated successfully!');
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
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findOrfail($id);
        $has_related_task = Task::where('client_id', $client->id)->first();
        $has_related_user = User::where('client_id', $client->id)->first();

        if($has_related_task || $has_related_user)
        {
            $result = $this->failedDeleteValidationResponse('Data cannot be deleted due to existence of related record.');
        }
        else
        {
            $result = $this->successResponse('Client deleted successfully!');
            try {
                $client->delete();
            } catch (\Throwable $th)
            {
                return $this->errorResponse($th);
            }
        }

        return $this->returnResponse($result);
    }
}
