<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AllowedEditingDate;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allowed_daterange =  AllowedEditingDate::first();

        $date_from = $allowed_daterange->allowed_date_from;
        $date_to = $allowed_daterange->allowed_date_to;

        return view('pages.admin.settings.list', compact('allowed_daterange','date_from','date_to'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $date_range = explode(' - ', $request['allowed_date_range']);

        $request['allowed_date_from'] = date("Y-m-d H:i:s", strtotime($date_range[0]));
        $request['allowed_date_to'] = date("Y-m-d H:i:s", strtotime($date_range[1]));

        $allowed_daterange = AllowedEditingDate::findOrfail($id);
        $allowed_daterange->update($request->except('date_from','date_to','allowed_date_range'));

        return redirect()->back()->with('with_success', "Allowed Date Range to Edit updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
