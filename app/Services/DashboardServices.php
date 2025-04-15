<?php

namespace App\Services;

use App\Models\Client;
use Carbon\Carbon;
use App\Models\Permission;
use Carbon\CarbonImmutable;

class DashboardServices
{
    // dashboarddata
    public function dashboardData($date, $agents_fte, $clients_fte)
    {
        $datastorage = [];

        $datastorage = [
            'date'                  => $date,
            'agents_fte'            => $agents_fte,
            'clients_fte'           => $clients_fte,
        ];

        return $datastorage;
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
                'theuser:id,emp_id,fullname,last_name,employment_status',
                'theclient:id,name',
                'thetasks'
            ])
            ->select('id','user_id','client_id')
            ->where('permission','<>','superadmin')
            ->whereNull('deleted_at')
            ->whereHas('theuser', function($query) {
                $query->where('employment_status', 'active');
            });

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
                    $date_filter = $where['date'].' 00:00:00';
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
                    $date_filter = explode("-", $where["date"]);
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

        $d = $this->dateFilters($where, 'daily');
        $date = $d['date'];
        $date_filter = $d['date_filter'];

        $agents_fte = $agents->map(function ($agent) use ($cluster_id,$date_filter) {
            $client = $agent->theclient ? $agent->theclient->name : '';
            $employee_name = $agent->theuser->fullname .' '. $agent->theuser->last_name;

            $tasks = $agent->thetasks()
                ->where('cluster_id',$cluster_id)
                ->where('status','Completed')
                ->where('shift_date', $date_filter)
                ->get();

            $total_volume = $tasks->sum('volume');

            $sum_aht = number_format($tasks->sum('aht_in_minutes'),2);

            // Count distinct workdays within the date filter
            $workdays = $tasks->pluck('shift_date')
                ->map(function ($date) {
                    return $date->toDateString();
                })
                ->unique()
                ->count();

            $work_minutes = $workdays * 450;

            $ruPercent = $work_minutes > 0
                ? number_format(($sum_aht / $work_minutes) * 100,2)
                : '0.00';

            return [
                'client'        => $client,
                'employee_name' => $employee_name,
                'sum_volume'    => $total_volume,
                'sum_aht'       => $sum_aht,
                'workdays'      => $workdays,
                'work_minutes'  => $work_minutes,
                'ru_percent'    => $ruPercent . '%',
            ];
        });

        $clients_fte = collect($agents_fte)->groupBy(function ($item) {
                // Group by client, or 'No Client' if no client is assigned
                return $item['client'] ?: '';
            })->map(function ($group, $client) {
                $count = $group->count();

            $total_ru = $group->sum(function ($item) {
                // Strip '%' and convert to float
                return floatval(str_replace('%', '', $item['ru_percent']));
            });

            $average_ru = $count > 0 ? number_format($total_ru / $count, 2) . '%' : '0.00%';

            return [
                'client' => $client,
                'average_ru' => $average_ru,
                'agents_count' => $count
            ];
        })->values()->toArray();

        return $this->dashboardData($date, $agents_fte, $clients_fte);
    }

    // WEEKLY
    public function getWeekly($where)
    {
        $cluster_id = auth()->user()->thepermisssion->cluster_id;
        $agents = $this->getAgents($cluster_id);

        $d = $this->dateFilters($where, 'weekly');
        $date = $d['date'];
        $date_filter = $d['date_filter'];

        $agents_fte = $agents->map(function ($agent) use ($cluster_id,$date_filter) {
            $client = $agent->theclient ? $agent->theclient->name : '';
            $employee_name = $agent->theuser->fullname .' '. $agent->theuser->last_name;

            $tasks = $agent->thetasks()
                ->where('cluster_id',$cluster_id)
                ->where('status','Completed')
                ->whereBetween('shift_date', $date_filter)
                ->get();

            $total_volume = $tasks->sum('volume');

            $sum_aht = number_format($tasks->sum('aht_in_minutes'),2);

            // Count distinct workdays within the date filter
            $workdays = $tasks->pluck('shift_date')
                ->map(function ($date) {
                    return $date->toDateString();
                })
                ->unique()
                ->count();

            $work_minutes = $workdays * 450;

            $ruPercent = $work_minutes > 0
                ? number_format(($sum_aht / $work_minutes) * 100,2)
                : '0.00';

            return [
                'client'        => $client,
                'employee_name' => $employee_name,
                'sum_volume'    => $total_volume,
                'sum_aht'       => $sum_aht,
                'workdays'      => $workdays,
                'work_minutes'  => $work_minutes,
                'ru_percent'    => $ruPercent . '%',
            ];
        });

        $clients_fte = collect($agents_fte)->groupBy(function ($item) {
                // Group by client, or 'No Client' if no client is assigned
                return $item['client'] ?: '';
            })->map(function ($group, $client) {
                $count = $group->count();

            $total_ru = $group->sum(function ($item) {
                // Strip '%' and convert to float
                return floatval(str_replace('%', '', $item['ru_percent']));
            });

            $average_ru = $count > 0 ? number_format($total_ru / $count, 2) . '%' : '0.00%';

            return [
                'client' => $client,
                'average_ru' => $average_ru,
                'agents_count' => $count
            ];
        })->values()->toArray();

        return $this->dashboardData($date, $agents_fte, $clients_fte);
    }

    // MONTHLY
    public function getMonthly($where)
    {
        $cluster_id = auth()->user()->thepermisssion->cluster_id;
        $agents = $this->getAgents($cluster_id);

        $d = $this->dateFilters($where, 'monthly');
        $date = $d['date'];
        $date_filter = $d['date_filter'];

        $agents_fte = $agents->map(function ($agent) use ($cluster_id,$date_filter) {
            $client = $agent->theclient ? $agent->theclient->name : '';
            $employee_name = $agent->theuser->fullname .' '. $agent->theuser->last_name;

            $tasks = $agent->thetasks()
                ->where('cluster_id', $cluster_id)
                ->where('status', 'Completed')
                ->whereYear('shift_date', $date_filter[0])
                ->whereMonth('shift_date', $date_filter[1])
                ->get();

            $total_volume = $tasks->sum('volume');

            $sum_aht = number_format($tasks->sum('aht_in_minutes'),2);

            // Count distinct workdays within the date filter
            $workdays = $tasks->pluck('shift_date')
                ->map(function ($date) {
                    return $date->toDateString();
                })
                ->unique()
                ->count();

            $work_minutes = $workdays * 450;

            $ruPercent = $work_minutes > 0
                ? number_format(($sum_aht / $work_minutes) * 100,2)
                : '0.00';

            return [
                'client'        => $client,
                'employee_name' => $employee_name,
                'sum_volume'    => $total_volume,
                'sum_aht'       => $sum_aht,
                'workdays'      => $workdays,
                'work_minutes'  => $work_minutes,
                'ru_percent'    => $ruPercent . '%',
            ];
        });

        $clients_fte = collect($agents_fte)->groupBy(function ($item) {
                // Group by client, or 'No Client' if no client is assigned
                return $item['client'] ?: '';
            })->map(function ($group, $client) {
                $count = $group->count();

            $total_ru = $group->sum(function ($item) {
                // Strip '%' and convert to float
                return floatval(str_replace('%', '', $item['ru_percent']));
            });

            $average_ru = $count > 0 ? number_format($total_ru / $count, 2) . '%' : '0.00%';

            return [
                'client' => $client,
                'average_ru' => $average_ru,
                'agents_count' => $count
            ];
        })->values()->toArray();

        return $this->dashboardData($date, $agents_fte, $clients_fte);
    }
}
