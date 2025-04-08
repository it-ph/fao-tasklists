<?php

namespace App\Services;

use App\Models\Client;
use Carbon\Carbon;
use App\Models\Permission;
use Carbon\CarbonImmutable;

class DashboardServices
{
    // dashboarddata
    public function dashboardData($date, $agents, $clients)
    {
        $datastorage = [];

        $datastorage = [
            'date'          => $date,
            'agents'        => $agents,
            'clients'       => $clients,
        ];

        return $datastorage;
    }

    public function getSumOfVolume($agent_id, $cluster_id)
    {

    }

    public function getSumOfAHT()
    {

    }

    public function scopeQuery($q)
    {
        // Get user permission
        $userPermission = auth()->user()->thepermisssion->permission;

        // Filter tasks based on user permission
        switch ($userPermission) {
            case 'admin':
            // admin: same with OM based on cluster
            // break;
            case 'operations manager':
                $q = $q->OMPermission();
                break;
            case 'team leader':
                $q = $q->TLPermission();
                break;
            case 'agent':
                $q = $q->AgentPermission();
                break;
            default:
                break;
        }

        return $q;
    }

    public function getAgents($cluster_id)
    {
        $agents = Permission::where('cluster_id', $cluster_id)
            ->with([
                'theuser:id,email,emp_id,fullname,last_name',
                'thetasks'
            ])
            ->select('id','user_id','cluster_id','client_id','tl_id','om_id','permission')
            ->where('permission','<>','superadmin')
            ->whereNull('deleted_at');

        $agents = $this->scopeQuery($agents);

        return $agents->get();
    }

    public function getClients($cluster_id)
    {
        $clients = Client::where('cluster_id', $cluster_id)
            ->select('id','name','cluster_id')
            ->whereNull('deleted_at')
            ->orderBy('name','asc');

        return $clients->get();
    }

    public function dateFilters($where, $slct_filter)
    {
        $date = $date_filter = null;
        switch ($slct_filter) {
            case 'daily':
                if ($where['filter'] == 'all') {
                    $date = date("F j, Y");
                    $date_filter = Carbon::today();
                } else {
                    $date = date("F j, Y", strtotime($where['date']));
                    $date_filter = $where['date'];
                }
                break;

            case 'weekly':
                if ($where['filter'] == 'all') {
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek();
                    $date = $start->format('F d') . ($start->format('F') === $end->format('F') ? " - {$end->format('d')}, " : " - {$end->format('F d')}, ") . $end->format('Y');
                    $date_filter = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
                } else {
                    $start = CarbonImmutable::parse($where['date']);
                    $end   = $start->addDays(6);
                    $date  = $start->format('F d') . ($start->format('F') === $end->format('F') ? " - {$end->format('d')}, " : " - {$end->format('F d')}, ") . $end->format('Y');
                    $date_filter = [$start,$end];
                }
                break;

            case 'monthly':
                if ($where['filter'] == 'all') {
                    $date = date("F Y");
                    $date_filter = Carbon::now()->month;
                } else {
                    $date = date("F Y", strtotime($where["date"]));
                    $date_filter = explode("-", $where["date"])[1];
                }
                break;

            case 'yearly':
                if ($where['filter'] == 'all') {
                    $date = date("Y");
                    $date_filter = Carbon::now()->year;
                } else {
                    $date = $where["date"];
                    $date_filter = $where["date"];
                }
                break;
        }

        return [
            'date' => $date,
            'date_filter' => $date_filter
        ];
    }

    // DAILY
    public function getDaily($where)
    {
        $cluster_id = auth()->user()->thepermisssion->cluster_id;
        $agents = $this->getAgents($cluster_id);
        $clients = $this->getClients($cluster_id);

        $d = $this->dateFilters($where, 'daily');
        $date = $d['date'];
        $date_filter = $d['date_filter'];

        $dashboard = $agents->map(function ($agent) {
            $empployee_name = $agent->theuser->fullname .' '. $agent->theuser->last_name;
            $tasks = $agent->thetasks;
            $total_volume = $tasks->sum('volume');

            $totalAhtInMinutes = $tasks->sum(function ($task) {
                // Explode AHT string into parts
                $parts = array_map('intval', array_pad(explode(':', $task->actual_handling_time ?? '0:0:0:0'), 4, 0));

                [$dd, $hh, $mm, $ss] = $parts;

                return ($dd * 1440) + ($hh * 60) + $mm + ($ss / 60);
            });

            return [
                'employee_name' => $empployee_name,
                'sum_volume'    => $total_volume,
                'sum_aht'       => $totalAhtInMinutes,
                // 'workdays'      => $workdays,
                // 'work_minutes'  => $workMinutes,
                // 'ru_percent'    => $ruPercent,
            ];
        });

        dd($dashboard);



        // return $this->dashboardData($date, $agents, $clients);
    }
}
