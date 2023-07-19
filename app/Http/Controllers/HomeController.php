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
                'theagent.employeeprofile:emp_id,emp_code,fullname,last_name',
                'thedashboardactivity:id,name',
                'theclientactivity:id,name'
            ])
            ->where('agent_id', Auth::id())
            ->latest()
            ->take(20)
            ->get();

        $in_progress = Task::query()
            ->where('status','In Progress')
            ->where('agent_id', Auth::id())
            ->count();

        $completed = Task::query()
            ->where('status','Completed')
            ->where('agent_id', Auth::id())
            ->count();

        return view('index', compact('tasks','in_progress','completed'));
    }

    // public function any(Request $request)
    // {
    //     if (view()->exists($request->path())) {
    //         return view($request->path());
    //     }

    //     return view('errors.404');
    // }

    // // /*Language Translation*/
    // public function lang($locale)
    // {
    //     if ($locale) {
    //         App::setLocale($locale);
    //         Session::put('lang', $locale);
    //         Session::save();
    //         return redirect()->back()->with('locale', $locale);
    //     } else {
    //         return redirect()->back();
    //     }
    // }
}
