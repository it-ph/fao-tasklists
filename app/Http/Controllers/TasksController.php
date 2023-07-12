<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
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

        if(!in_array(strtolower($status),['','all','in progress','completed']))
        {
            return view('errors.404');
        }

        if(in_array($status,(['','all'])))
        {
            $tasks = Task::query()
                ->with([
                    'thecluster:id,name',
                    'theclient:id,name',
                    'theagent.employeeprofile:emp_id,emp_code,fullname,last_name',
                    'thedashboardactivity:id,name',
                    'theclientactivity:id,name'
                ])
                ->where('agent_id', Auth::id())
                ->get();
        }
        else
        {
            $tasks = Task::query()
                ->with([
                    'thecluster:id,name',
                    'theclient:id,name',
                    'theagent.employeeprofile:emp_id,emp_code,fullname,last_name',
                    'thedashboardactivity:id,name',
                    'theclientactivity:id,name'
                ])
                ->where('agent_id', Auth::id())
                ->where('status',$status)
                ->get();
        }

        $user_client_activities = ClientActivity::query()
            ->where('agent_id', Auth::id())
            ->select('id','agent_id','name')
            ->orderBy('name', 'ASC')
            ->get();

        return view('pages.agent.tasks.list', compact('tasks','user_client_activities'));
    }

    // ADMIN, TL, & OM ACCESS
    public function index(Request $request)
    {
        // $tasks = new TaskCollection(Task::with(['thecluster','theclient','theagent.employeeprofile','thedashboardactivity','theclientactivity','thetasklogs'])->get());
        $status = $request['status'];

        if(!in_array(strtolower($status),['','all','in progress','completed']))
        {
            return view('errors.404');
        }

        if(in_array($status,(['','all'])))
        {
            $tasks = Task::query()
                ->with([
                    'thecluster:id,name',
                    'theclient:id,name',
                    'theagent.employeeprofile:emp_id,emp_code,fullname,last_name',
                    'thedashboardactivity:id,name',
                    'theclientactivity:id,name'
                ])
                ->get();
        }
        else
        {
            $tasks = Task::query()
                ->with([
                    'thecluster:id,name',
                    'theclient:id,name',
                    'theagent.employeeprofile:emp_id,emp_code,fullname,last_name',
                    'thedashboardactivity:id,name',
                    'theclientactivity:id,name'
                ])
                ->where('status',$status)
                ->get();
        }

        return view('pages.admin.tasks.list', compact('tasks'));
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
        $request['start_date'] = \Carbon\Carbon::now();
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
        $task = Task::findOrFail($taskId);
        $status = "Completed";
        $actual_handling_time = "";
        $volume = $request['volume'];
        $remarks = $request['remarks'];

        $start = \Carbon\Carbon::parse($task->start_date);
        $now = \Carbon\Carbon::now();
        $actual_handling_time = $now->diff($start)->format('%D:%H:%I:%S');

        $task->update([
            'status' => $status,
            'end_date' => Carbon::now(),
            'actual_handling_time' => $actual_handling_time,
            'volume' => $volume,
            'remarks' => $remarks
        ]);

        return redirect()->back()->with('with_success', "Task has been completed successfully!");
    }
}
