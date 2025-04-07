<?php

namespace App\Services;

class DashboardServices
{

    public function scopeQuery($q)
    {
        // Get user permission
        $userPermission = auth()->user()->thepermission->permission;

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
        $agents = User::where('cluster_id', $cluster_id)
            ->select('id', 'fullname')
            ->whereNull('deleted_at')
            ->orderBy('fullname','asc');

        $agents = $this->scopeQuery($agents);

        return $agents->get();
    }

    // DAILY
    public function getDaily($where)
    {
        $cluster_id = auth()->user()->thepermission->cluster_id;
        $agents = $this->getAgents($cluster_id);
        $cluster_activities = $this->getClusterActivities($cluster_id);

        $d = $this->dateFilters($where, 'daily');
        $date = $d['date'];
        $date_filter = $d['date_filter'];

        // Fetch task counts per activity per agent
        $taskCounts = $this->taskCountsQuery($cluster_id)
            ->whereDate('shift_date', $date_filter)
            ->get();

        // Fetch total and average AHT per agent in one query
        $agentAHTData = $this->agentAHTDataQuery($cluster_id)
            ->whereDate('shift_date', $date_filter)
            ->get();

        return $this->dashboardData($date, $agents, $cluster_activities, $agentAHTData, $taskCounts);
    }
}
