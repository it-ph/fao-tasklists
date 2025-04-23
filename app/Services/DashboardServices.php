<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\Permission;
use Carbon\CarbonImmutable;

class DashboardServices
{
    // get dashboard data
    public function getDashboardData($date, $agents_fte, $clients_fte)
    {
        $datastorage = [];

        $datastorage = [
            'date'          => $date,
            'agents_fte'    => $agents_fte,
            'clients_fte'   => $clients_fte,
        ];

        return $datastorage;
    }

    public function scopeQuery($q)
    {
        // Get user permission
        $userPermission = auth()->user()->permission;

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
            case 'accountant':
                $q = $q->AgentPermission();
                break;
            default:
                break;
        }

        return $q;
    }

    public function getAgents($cluster_id)
    {
        $agents = User::where('cluster_id', $cluster_id)
            ->with([
                'theclient:id,name',
                'thetasks'
            ])
            ->select('id','client_id','fullname')
            ->where('permission','<>','superadmin')
            ->whereNull('deleted_at')
            ->where('status', 'active');

        $agents = $this->scopeQuery($agents);

        return $agents->get();
    }

    public function dateFilters($where, $slct_filter)
    {
        $date = $date_filter = null;
        switch ($slct_filter) {
            case 'daily':
                $date = date("F j, Y", strtotime($where['date']));
                $date_filter = $where['date'].' 00:00:00';
                break;

            case 'weekly':
                $start = CarbonImmutable::parse($where['date']);
                $end   = $start->addDays(6);
                $date  = $start->format('F d') . ($start->format('F') === $end->format('F') ? " - {$end->format('d')}, " : " - {$end->format('F d')}, ") . $end->format('Y');
                $date_filter = [$start,$end];
                break;

            case 'monthly':
                $date = date("F Y", strtotime($where["date"]));
                $date_filter = explode("-", $where["date"]);
                break;

            case 'yearly':
                $date = $where["date"];
                $date_filter = $where["date"];
                break;
        }

        return [
            'date' => $date,
            'date_filter' => $date_filter
        ];
    }

    public function getFteData($where, $period)
    {
        $cluster_id = auth()->user()->cluster_id;
        $agents = $this->getAgents($cluster_id);

        $d = $this->dateFilters($where, $period);
        $date_filter = $d['date_filter'];

        $agents_fte = collect();

        foreach ($agents->chunk(100) as $agentChunk) {
            foreach ($agentChunk as $agent) {
                $client = $agent->theclient ? $agent->theclient->name : '';
                $employee_name = $agent->fullname;

                $tasksQuery = $agent->thetasks()
                    ->taskfunction()
                    ->where('cluster_id', $cluster_id)
                    ->where('status', 'Completed');

                // Apply date filter based on period
                if ($period == 'daily') {
                    $tasksQuery->where('shift_date', $date_filter);
                } elseif ($period == 'weekly') {
                    $tasksQuery->whereBetween('shift_date', $date_filter);
                } elseif ($period == 'monthly') {
                    $tasksQuery->whereYear('shift_date', $date_filter[0])
                        ->whereMonth('shift_date', $date_filter[1]);
                } elseif ($period == 'yearly') {
                    $tasksQuery->whereYear('shift_date', $date_filter);
                }

                $tasks = $tasksQuery->selectRaw('
                    COALESCE(SUM(volume), 0) as total_volume,
                    COALESCE(SUM(aht_in_minutes), 0) as total_aht,
                    COALESCE(COUNT(DISTINCT shift_date), 0) as workdays
                ')->first();

                // If there are no tasks for the agent, skip
                if (!$tasks) continue;

                $work_minutes = $tasks->workdays * 450;
                $sum_aht = number_format($tasks->total_aht, 2);
                $ruPercent = $work_minutes > 0
                    ? number_format(($tasks->total_aht / $work_minutes) * 100, 2)
                    : '0.00';

                $agents_fte->push([
                    'client'        => $client,
                    'employee_name' => $employee_name,
                    'sum_volume'    => $tasks->total_volume,
                    'sum_aht'       => $sum_aht,
                    'workdays'      => $tasks->workdays,
                    'work_minutes'  => $work_minutes,
                    'ru_percent'    => $ruPercent . '%',
                ]);
            }
        }

        return $agents_fte;
    }

    public function processClientFte($agents_fte)
    {
        return collect($agents_fte)->groupBy(function ($item) {
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
    }

    // DAILY
    public function getDaily($where)
    {
        $agents_fte = $this->getFteData($where, 'daily');
        $clients_fte = $this->processClientFte($agents_fte);

        return $this->getDashboardData($this->dateFilters($where, 'daily')['date'], $agents_fte, $clients_fte);
    }

    // WEEKLY
    public function getWeekly($where)
    {
        $agents_fte = $this->getFteData($where, 'weekly');
        $clients_fte = $this->processClientFte($agents_fte);

        return $this->getDashboardData($this->dateFilters($where, 'weekly')['date'], $agents_fte, $clients_fte);
    }

    // MONTHLY
    public function getMonthly($where)
    {
        $agents_fte = $this->getFteData($where, 'monthly');
        $clients_fte = $this->processClientFte($agents_fte);

        return $this->getDashboardData($this->dateFilters($where, 'monthly')['date'], $agents_fte, $clients_fte);
    }

    // YEARLY
    public function getYearly($where)
    {
        $agents_fte = $this->getFteData($where, 'yearly');
        $clients_fte = $this->processClientFte($agents_fte);

        return $this->getDashboardData($this->dateFilters($where, 'yearly')['date'], $agents_fte, $clients_fte);
    }






































    // // DAILY
    // public function getDaily($where)
    // {
    //     $cluster_id = auth()->user()->cluster_id;
    //     $agents = $this->getAgents($cluster_id);

    //     $d = $this->dateFilters($where, 'daily');
    //     $date = $d['date'];
    //     $date_filter = $d['date_filter'];

    //     $agents_fte = $agents->map(function ($agent) use ($cluster_id,$date_filter) {
    //         $client = $agent->theclient ? $agent->theclient->name : '';
    //         $employee_name = $agent->theuser->fullname .' '. $agent->theuser->last_name;

    //         $tasks = $agent->thetasks()
    //             ->where('cluster_id',$cluster_id)
    //             ->where('status','Completed')
    //             ->where('shift_date', $date_filter)
    //             ->get();

    //         $total_volume = $tasks->sum('volume');

    //         $sum_aht = number_format($tasks->sum('aht_in_minutes'),2);

    //         // Count distinct workdays within the date filter
    //         $workdays = $tasks->pluck('shift_date')
    //             ->map(function ($date) {
    //                 return $date->toDateString();
    //             })
    //             ->unique()
    //             ->count();

    //         $work_minutes = $workdays * 450;

    //         $ruPercent = $work_minutes > 0
    //             ? number_format(($sum_aht / $work_minutes) * 100,2)
    //             : '0.00';

    //         return [
    //             'client'        => $client,
    //             'employee_name' => $employee_name,
    //             'sum_volume'    => $total_volume,
    //             'sum_aht'       => $sum_aht,
    //             'workdays'      => $workdays,
    //             'work_minutes'  => $work_minutes,
    //             'ru_percent'    => $ruPercent . '%',
    //         ];
    //     });

    //     $clients_fte = collect($agents_fte)->groupBy(function ($item) {
    //             // Group by client, or 'No Client' if no client is assigned
    //             return $item['client'] ?: '';
    //         })->map(function ($group, $client) {
    //             $count = $group->count();

    //         $total_ru = $group->sum(function ($item) {
    //             // Strip '%' and convert to float
    //             return floatval(str_replace('%', '', $item['ru_percent']));
    //         });

    //         $average_ru = $count > 0 ? number_format($total_ru / $count, 2) . '%' : '0.00%';

    //         return [
    //             'client' => $client,
    //             'average_ru' => $average_ru,
    //             'agents_count' => $count
    //         ];
    //     })->values()->toArray();

    //     return $this->dashboardData($date, $agents_fte, $clients_fte);
    // }

    // // WEEKLY
    // public function getWeekly($where)
    // {
    //     $cluster_id = auth()->user()->cluster_id;
    //     $agents = $this->getAgents($cluster_id);

    //     $d = $this->dateFilters($where, 'weekly');
    //     $date = $d['date'];
    //     $date_filter = $d['date_filter'];

    //     $agents_fte = $agents->map(function ($agent) use ($cluster_id,$date_filter) {
    //         $client = $agent->theclient ? $agent->theclient->name : '';
    //         $employee_name = $agent->theuser->fullname .' '. $agent->theuser->last_name;

    //         $tasks = $agent->thetasks()
    //             ->where('cluster_id',$cluster_id)
    //             ->where('status','Completed')
    //             ->whereBetween('shift_date', $date_filter)
    //             ->get();

    //         $total_volume = $tasks->sum('volume');

    //         $sum_aht = number_format($tasks->sum('aht_in_minutes'),2);

    //         // Count distinct workdays within the date filter
    //         $workdays = $tasks->pluck('shift_date')
    //             ->map(function ($date) {
    //                 return $date->toDateString();
    //             })
    //             ->unique()
    //             ->count();

    //         $work_minutes = $workdays * 450;

    //         $ruPercent = $work_minutes > 0
    //             ? number_format(($sum_aht / $work_minutes) * 100,2)
    //             : '0.00';

    //         return [
    //             'client'        => $client,
    //             'employee_name' => $employee_name,
    //             'sum_volume'    => $total_volume,
    //             'sum_aht'       => $sum_aht,
    //             'workdays'      => $workdays,
    //             'work_minutes'  => $work_minutes,
    //             'ru_percent'    => $ruPercent . '%',
    //         ];
    //     });

    //     $clients_fte = collect($agents_fte)->groupBy(function ($item) {
    //             // Group by client, or 'No Client' if no client is assigned
    //             return $item['client'] ?: '';
    //         })->map(function ($group, $client) {
    //             $count = $group->count();

    //         $total_ru = $group->sum(function ($item) {
    //             // Strip '%' and convert to float
    //             return floatval(str_replace('%', '', $item['ru_percent']));
    //         });

    //         $average_ru = $count > 0 ? number_format($total_ru / $count, 2) . '%' : '0.00%';

    //         return [
    //             'client' => $client,
    //             'average_ru' => $average_ru,
    //             'agents_count' => $count
    //         ];
    //     })->values()->toArray();

    //     return $this->dashboardData($date, $agents_fte, $clients_fte);
    // }

    // // MONTHLY
    // public function getMonthly($where)
    // {
    //     $cluster_id = auth()->user()->cluster_id;
    //     $agents = $this->getAgents($cluster_id);

    //     $d = $this->dateFilters($where, 'monthly');
    //     $date = $d['date'];
    //     $date_filter = $d['date_filter'];

    //     $agents_fte = $agents->map(function ($agent) use ($cluster_id,$date_filter) {
    //         $client = $agent->theclient ? $agent->theclient->name : '';
    //         $employee_name = $agent->theuser->fullname .' '. $agent->theuser->last_name;

    //         $tasks = $agent->thetasks()
    //             ->where('cluster_id', $cluster_id)
    //             ->where('status', 'Completed')
    //             ->whereYear('shift_date', $date_filter[0])
    //             ->whereMonth('shift_date', $date_filter[1])
    //             ->get();

    //         $total_volume = $tasks->sum('volume');

    //         $sum_aht = number_format($tasks->sum('aht_in_minutes'),2);

    //         // Count distinct workdays within the date filter
    //         $workdays = $tasks->pluck('shift_date')
    //             ->map(function ($date) {
    //                 return $date->toDateString();
    //             })
    //             ->unique()
    //             ->count();

    //         $work_minutes = $workdays * 450;

    //         $ruPercent = $work_minutes > 0
    //             ? number_format(($sum_aht / $work_minutes) * 100,2)
    //             : '0.00';

    //         return [
    //             'client'        => $client,
    //             'employee_name' => $employee_name,
    //             'sum_volume'    => $total_volume,
    //             'sum_aht'       => $sum_aht,
    //             'workdays'      => $workdays,
    //             'work_minutes'  => $work_minutes,
    //             'ru_percent'    => $ruPercent . '%',
    //         ];
    //     });

    //     $clients_fte = collect($agents_fte)->groupBy(function ($item) {
    //             // Group by client, or 'No Client' if no client is assigned
    //             return $item['client'] ?: '';
    //         })->map(function ($group, $client) {
    //             $count = $group->count();

    //         $total_ru = $group->sum(function ($item) {
    //             // Strip '%' and convert to float
    //             return floatval(str_replace('%', '', $item['ru_percent']));
    //         });

    //         $average_ru = $count > 0 ? number_format($total_ru / $count, 2) . '%' : '0.00%';

    //         return [
    //             'client' => $client,
    //             'average_ru' => $average_ru,
    //             'agents_count' => $count
    //         ];
    //     })->values()->toArray();

    //     return $this->dashboardData($date, $agents_fte, $clients_fte);
    // }
}
