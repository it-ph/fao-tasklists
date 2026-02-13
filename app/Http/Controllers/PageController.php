<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Client;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\ClientActivity;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalVariableController;

class PageController extends GlobalVariableController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Agent Permissions
     */
    public function showPermissions(Request $request)
    {
        return view('pages.admin.permissions.list');
    }

    /**
     * Users
     */
    public function showUsers(Request $request)
    {
        return view('pages.admin.users.list');
    }

    /**
     * Clusters
     */
    public function showClusters()
    {
        return view('pages.admin.clusters.list');
    }

    /**
     * Change Requests
     */
    public function showChangeRequests()
    {
        $tasks = Task::query()
            ->select('id')
            ->where('status','Completed')
            ->where('end_date', '>=', now()->subMonth());

            // Get user permission
            $userPermission = auth()->user()->permission;

            // Filter tasks based on user permission
            switch ($userPermission) {
                case 'superadmin':
                case 'admin':
                    $tasks = $tasks->get();
                    break;
                case 'operations manager':
                    $tasks = $tasks->OMPermission()->get();
                    break;
                case 'team leader':
                    $tasks = $tasks->TLPermission()->get();
                    break;
                case 'accountant':
                    $tasks = $tasks->AccountantPermission()->get();
                    break;
                default:
                    break;
            }
        return view('pages.admin.change-requests.list',compact('tasks'));
    }

    // ADMIN, TL, & OM ACCESS
    public function showAgentTaskLists(Request $request)
    {
        // accountant
        if(auth()->user()->isAccountant())
        {
            return redirect()->route('unauthorized');
        }

        $status = $request['status'];
        if(!in_array(strtolower($status),['','all','in progress','on hold','completed']))
        {
            return view('errors.404');
        }

        $user_client_activities = ClientActivity::query()
            ->select('id','agent_id','name')
            ->orderBy('name', 'ASC')
            ->get();

        return view('pages.admin.tasks.list',compact('user_client_activities'));
    }

    // AGENT ACCESS
    public function showAgentTasks(Request $request)
    {
        $status = $request['status'];
        if(!in_array(strtolower($status),['','all','in progress','on hold','completed']))
        {
            return view('errors.404');
        }

        $clients = auth()->user()->isAdmin() ? $clients = Client::with('thecluster')->get() : Client::with('thecluster')->cluster()->get();

        $user_client_activities = ClientActivity::query()
            ->select('id','agent_id','name')
            ->where('agent_id', auth()->user()->id)
            ->orderBy('name', 'ASC')
            ->get();

        return view('pages.agent.tasks.list', compact('status','clients','user_client_activities'));
    }

    /**
     * Task Lists
     */
    public function AgentTasks(Request $request)
    {
        $status = $request['status'];

        // accountant
        if(auth()->user()->isAccountant())
        {
            return redirect()->route('unauthorized');
        }

        $status = $request['status'];
        if(!in_array(strtolower($status),['all','in progress','on hold','completed']))
        {
            return view('errors.404');
        }
        $clients = auth()->user()->isAdmin() ? $clients = Client::with('thecluster') : Client::with('thecluster')->cluster()->get();

        $user_client_activities = ClientActivity::query()
            ->select('id','agent_id','name')
            ->orderBy('name', 'ASC')
            ->get();

        return view('pages.admin.tasks.list', compact('status'));
    }
}
