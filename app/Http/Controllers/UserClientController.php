<?php

namespace App\Http\Controllers;

use App\Models\UserClient;
use App\Http\Resources\UserClientResource;
use App\Http\Resources\UserClientCollection;
use App\Http\Requests\StoreUserClientRequest;
use App\Http\Requests\UpdateUserClientRequest;

class UserClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new UserClientCollection(UserClient::with(['theuser','theclient'])->get());
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
     * @param  \App\Http\Requests\StoreUserClientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserClientRequest $request)
    {
        return new UserClientResource(UserClient::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserClient  $userClient
     * @return \Illuminate\Http\Response
     */
    public function show(UserClient $userClient)
    {
        return new UserClientResource($userClient->loadMissing(['theuser','theclient']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserClient  $userClient
     * @return \Illuminate\Http\Response
     */
    public function edit(UserClient $userClient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserClientRequest  $request
     * @param  \App\Models\UserClient  $userClient
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserClientRequest $request, UserClient $userClient)
    {
        $userClient->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserClient  $userClient
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserClient $userClient)
    {
        $userClient->delete();

        return 'deleted';
    }
}
