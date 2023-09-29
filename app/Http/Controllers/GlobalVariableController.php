<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Cluster;
use App\Models\Permission;
use App\Models\UserProfile;
use App\Models\ClientActivity;
use App\Models\DashboardActivity;
use Illuminate\Support\Facades\View;

class GlobalVariableController extends Controller
{
    public $clusters,$clients,$client_activities,$users,$permissions,$tls,$oms;

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

        $this->client_activities = ClientActivity::query()
            ->select('id','name')
            ->orderBy('name', 'ASC')
            ->get();

        $this->users = User::query()
            ->select('id','email','emp_id','fullname','last_name','employment_status')
            ->where('employment_status','active')
            ->orderBy('email', 'ASC')
            ->get();

        $this->permissions = Permission::with([
                'theuser:id,email',
                'theuser:emp_id,fullname,last_name',
                'thecluster:id,name',
                'theclient:id,name',
                'thetl:user_id',
                'thetl.theuser:id,email',
                'thetl.theuser:emp_id,fullname,last_name',
                'theom:user_id',
                'theom.theuser:id,email',
                'theom.theuser:emp_id,fullname,last_name',
            ])
            ->select('id','user_id','cluster_id','client_id','tl_id','om_id','permission')
            ->where('permission','<>','superadmin')
            ->get();

        $permissions = Permission::query()
                ->from('permissions as ftp')
                ->leftjoin('users as hr','ftp.user_id', '=', 'hr.emp_id')
                ->select(['ftp.id','ftp.user_id','ftp.permission','hr.fullname','hr.last_name','hr.emp_id'])
                ->whereIn('ftp.permission',['admin','team leader','operations manager'])
                ->orderBy('hr.fullname')
                ->get();

        $this->tls = $permissions;
        $this->oms = $permissions;

        View::share('clusters', $this->clusters);
        View::share('clients', $this->clients);
        View::share('client_activities', $this->client_activities);
        View::share('users', $this->users);
        View::share('permissions', $this->permissions);
        View::share('tls', $this->tls);
        View::share('oms', $this->oms);
    }
}
