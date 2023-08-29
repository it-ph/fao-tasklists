<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\ClientActivity;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TaskCollection;
use App\Http\Requests\StoreTasksRequest;
use App\Http\Requests\UpdateTasksRequest;
use App\Http\Controllers\GlobalVariableController;

class TasksController extends GlobalVariableController
{
    public function __construct()
    {
        parent::__construct();
    }

    // AGENT ACCESS
    public function agentTask(Request $request)
    {
        $status = $request['status'];
        if(!in_array(strtolower($status),['','all','in progress','on hold','completed']))
        {
            return view('errors.404');
        }

        $tasks = Task::query()
            ->with([
                'thecluster:id,name',
                'theclient:id,name',
                'theagent:id,email',
                'theagent.employeeprofile:emp_id,emp_code,fullname,last_name',
                'theclientactivity:id,name'
            ])
            ->where('agent_id', Auth::id());

        // filter by status
        if(in_array($status,(['','all'])))
        {
            $tasks = $tasks->get();
        }
        else
        {
            $tasks = $tasks->where('status',$status)->get();
        }

        $clients = Auth::user()->isAdmin() ? $clients = Client::with('thecluster') : Client::with('thecluster')->cluster()->get();
        $user_client_activities = ClientActivity::query()
            ->select('id','agent_id','name')
            ->where('agent_id', Auth::id())
            ->orderBy('name', 'ASC')
            ->get();

        return view('pages.agent.tasks.list', compact('tasks','clients','user_client_activities'));
    }

    // ADMIN, TL, & OM ACCESS
    public function index(Request $request)
    {
        // accountant
        if(Auth::user()->isAccountant())
        {
            return redirect()->route('unauthorized');
        }

        $status = $request['status'];
        if(!in_array(strtolower($status),['','all','in progress','on hold','completed']))
        {
            return view('errors.404');
        }

        $tasks = Task::query()
            ->with([
                'thecluster:id,name',
                'theclient:id,name',
                'theagent:id,email',
                'theagent.employeeprofile:emp_id,emp_code,fullname,last_name',
                'theclientactivity:id,name'
            ]);

        // admin
        if(Auth::user()->isAdmin())
        {
            $tasks = $tasks;
        }
        // operations manager
        elseif(Auth::user()->isOperationsManager())
        {
            $tasks = $tasks->OMPermission();
        }
        // team leader
        elseif(Auth::user()->isTeamLeader())
        {
            $tasks = $tasks->TLPermission();
        }

        // filter by status
        if(in_array($status,(['','all'])))
        {
            $tasks = $tasks->get();
        }
        else
        {
            $tasks = $tasks->where('status',$status)->get();
        }

        $user_client_activities = ClientActivity::query()
            ->select('id','agent_id','name')
            ->where('agent_id', Auth::id())
            ->orderBy('name', 'ASC')
            ->get();

        return view('pages.admin.tasks.list', compact('tasks','user_client_activities'));
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
     * @param  \App\Http\Requests\StoreTasksRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTasksRequest $request)
    {
        $request['created_by'] = Auth::id();
        $request['start_date'] = Carbon::now();
        $task = new TaskResource(Task::create($request->all()));
        return redirect()->back()->with('with_success', "Task created successfully!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        // return new TaskResource($task->loadMissing(['thecluster','theclient','theagent.employeeprofile','thedashboardactivity','theclientactivity']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTasksRequest  $request
     * @param  \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTasksRequest $request, Task $task)
    {
        $task->update($request->all());
        return redirect()->back()->with('with_success', "Task updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        if($task->status != "Not Started")
        {
            return redirect()->back()->withErrors("Task cannot be deleted. Task is either already In Progress or Completed.");
        }
        else
        {
            $task->delete();
            return redirect()->back()->with('with_success', "Task deleted successfully!");
        }
    }

    public function upload()
    {
        return view('pages.admin.tasks.upload');
    }

    // Stop Task
    public function stopTask(Request $request, $taskId)
    {
        $this->validate($request,
            [
                'status' => 'required',
                'volume' => 'required',
            ],
            $message = array(
                'status.required' => 'Set Status to On Hold or Completed!',
                'volume.required' => 'Volume is required!',
            )
        );

        $task = Task::findOrFail($taskId);
        $status = $request['status'];
        $actual_handling_time = "";
        $volume = $request['volume'];
        $remarks = $request['remarks'];

        $start = Carbon::parse($task->start_date);
        $now = Carbon::now();
        $actual_handling_time = $now->diff($start)->format('%D:%H:%I:%S');

        $task->update([
            'status' => $status,
            'end_date' => Carbon::now(),
            'actual_handling_time' => $actual_handling_time,
            'volume' => $volume,
            'remarks' => $remarks
        ]);

        return redirect()->back()->with('with_success', "Task has been ".$task->status." successfully!");
    }

    // Pause Task
    public function pauseTask(Request $request, $taskId)
    {
        // $this->validate($request,
        //     [
        //         'volume' => 'required',
        //         'remarks' => 'required',
        //     ],
        //     $message = array(
        //         'volume.required' => 'Volume is required!',
        //         'remarks.required' => 'Remarks is required!',
        //     )
        // );

        $task = Task::findOrFail($taskId);
        $status = "On Hold";
        // $actual_handling_time = "";
        // $volume = $request['volume'];
        // $remarks = $request['remarks'];

        // $start = Carbon::parse($task->start_date);
        // $now = Carbon::now();
        // $actual_handling_time = $now->diff($start)->format('%D:%H:%I:%S');

        $task->update([
            'status' => $status,
            // 'end_date' => Carbon::now(),
            // 'actual_handling_time' => $actual_handling_time,
            // 'volume' => $volume,
            // 'remarks' => $remarks
        ]);

        return redirect()->back()->with('with_success', "Task has been completed successfully!");
    }

    // Resume Task
    public function resumeTask(Request $request, $taskId)
    {
        // $this->validate($request,
        //     [
        //         'volume' => 'required',
        //         'remarks' => 'required',
        //     ],
        //     $message = array(
        //         'volume.required' => 'Volume is required!',
        //         'remarks.required' => 'Remarks is required!',
        //     )
        // );

        $task = Task::findOrFail($taskId);
        $status = "In Progress";
        // $actual_handling_time = "";
        // $volume = $request['volume'];
        // $remarks = $request['remarks'];

        // $start = Carbon::parse($task->start_date);
        // $now = Carbon::now();
        // $actual_handling_time = $now->diff($start)->format('%D:%H:%I:%S');

        $task->update([
            'status' => $status,
            // 'end_date' => Carbon::now(),
            // 'actual_handling_time' => $actual_handling_time,
            // 'volume' => $volume,
            // 'remarks' => $remarks
        ]);

        return redirect()->back()->with('with_success', "Task has been completed successfully!");
    }
}
