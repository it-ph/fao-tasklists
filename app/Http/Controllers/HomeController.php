<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $tasks = Task::query()
            ->with([
                'thecluster:id,name',
                'theclient:id,name',
                'theagent:emp_id,emp_code,fullname,last_name',
                'theclientactivity:id,name'
            ])
            ->where('agent_id', Auth::user()->emp_id)
            ->latest()
            ->take(20)
            ->get();

        $in_progress = Task::query()
            ->where('status','In Progress')
            ->where('agent_id', Auth::user()->emp_id)
            ->count();

        $on_hold = Task::query()
            ->where('status','On Hold')
            ->where('agent_id', Auth::user()->emp_id)
            ->count();

        $completed = Task::query()
            ->where('status','Completed')
            ->where('agent_id', Auth::user()->emp_id)
            ->count();

        $all = $in_progress + $on_hold + $completed;

        return view('index', compact('tasks','in_progress','on_hold','completed','all'));
    }
}
