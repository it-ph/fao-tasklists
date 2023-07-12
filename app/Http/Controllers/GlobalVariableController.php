<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Cluster;
use App\Models\Permission;
use App\Models\ClientActivity;
use App\Models\DashboardActivity;
use Illuminate\Support\Facades\View;

class GlobalVariableController extends Controller
{
    public $clusters,$clients,$dashboard_activities,$client_activities,$users,$permissions,$tls,$oms;

    public function __construct()
    {
        $this->clusters = Cluster::query()
            ->select('id','name')
            ->orderBy('name', 'ASC')
            ->get();

        $this->clients = Client::query()
            ->select('id','name')
            ->orderBy('name', 'ASC')
            ->get();

        $this->dashboard_activities = DashboardActivity::query()
            ->select('id','name')
            ->orderBy('name', 'ASC')
            ->get();

        $this->client_activities = ClientActivity::query()
            ->select('id','name')
            ->orderBy('name', 'ASC')
            ->get();

        $this->users = User::query()
            ->with('employeeprofile:emp_id,emp_code,fullname,last_name')
            ->select('id','email','employment_status')
            ->where('employment_status','active')
            ->orderBy('email', 'ASC')
            ->get();

        $this->permissions = Permission::with([
                'theuser:id,email',
                'theuser.employeeprofile:emp_id,emp_code,fullname,last_name',
                'thecluster:id,name',
                'theclient:id,name',
                'thetl.theuser','thetl.theuser.employeeprofile',
                'theom.theuser','theom.theuser.employeeprofile',
                'theuser.theclientactivities:agent_id'
            ])
            ->select('id','user_id','cluster_id','client_id','tl_id','om_id','permission')
            ->where('permission','<>','superadmin')
            ->get();

        $this->tls = Permission::with([
                'theuser:id,email',
                'theuser.employeeprofile:emp_id,emp_code,fullname,last_name',
            ])
            ->select('id','user_id','permission')
            ->where('permission','team lead')
            ->get();

        $this->oms = Permission::with([
                'theuser:id,email',
                'theuser.employeeprofile:emp_id,emp_code,fullname,last_name',
            ])
            ->select('id','user_id','permission')
            ->where('permission','operations manager')
            ->get();

        View::share('clusters', $this->clusters);
        View::share('clients', $this->clients);
        View::share('dashboard_activities', $this->dashboard_activities);
        View::share('client_activities', $this->client_activities);
        View::share('users', $this->users);
        View::share('permissions', $this->permissions);
        View::share('tls', $this->tls);
        View::share('oms', $this->oms);
    }
}
