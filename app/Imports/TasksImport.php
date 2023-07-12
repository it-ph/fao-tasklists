<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class TasksImport implements ToModel, WithHeadingRow,WithValidation,SkipsEmptyRows
{
    private $has_error = array();
    private $row_number = 1;
    public function model(array $row)
    {
        $ctr_error = 0;
        array_push($this->has_error,"Something went wrong, Please check all entries that you have encoded.");

        $this->row_number += 1;

        $task_number = 'FT'.random_int(100000, 999999);
        // $cluster_id = Cluster::where('name', $row['cluster'])->pluck('id');
        // $client_id = Client::where('name', $row['client'])->pluck('id');
        // $user_id = User::where('emp_id', $row['employee_number'])->where('status', 'active')->pluck('id');
        // $agent_id = Permission::where('user_id','user_id')->pluck('user_id');
        // $dashboard_activity_id = DashboardActivitty::where('name', $row['dashboard_activity'])->pluck('id');
        // $client_activity_id = ClientActivity::where('name', $row['client_activity'])->pluck('id');

        $cluster_id = 1;
        $client_id = 1;
        $user_id = 1506;
        $agent_id = 1506;
        $dashboard_activity_id = 3;
        $client_activity_id = 3;

        $accounting_period = $row['accounting_period'];
        $prerequisite_dependency = $row['prerequisite_dependency'];
        $client_detailed_activity = $row['client_detailed_activity'];
        $poc = $row['poc'];
        $go_live_date = $row['go_live_date'];
        $frequency = $row['frequency'];
        $due_date = $row['due_date'];
        $estimated_handling_time = $row['estimated_handling_time'];

        $status = 'Not Started';
        $status_date = null;
        $start_date = null;
        $end_date = null;
        $actual_handling_time = null;
        $volume = null;
        $remarks = null;

        $dtp_link = $row['dtp_link'];
        $training_recording_link = $row['training_recording_link'];
        $created_by = Auth::id();

        // if(strtolower($row['month_type']) == 'mid' || strtolower($row['month_type']) == 'end')
        // {
        //     $month_type = $row['month_type'];
        // }
        // else
        // {
        //     array_push($this->has_error, "Check Cell A". $this->row_number.", ". "Month Type: ". $row['month_type']. " is invalid.");
        //     $ctr_error += 1;
        // }

        // if($agent->count() > 0)
        // {
        //     $agent_id = $agent[0];
        // }
        // else
        // {
        //     array_push($this->has_error, "Check Cell C". $this->row_number.", ". "Employee Number: ". $row['employee_number']. " not exist.");
        //     $ctr_error += 1;
        // }

        if($ctr_error <= 0)
        {
            Task::updateOrCreate(
                [
                    'task_number' => $task_number,
                ],
                [
                    'cluster_id' => $cluster_id,
                    'client_id' => $client_id,
                    'agent_id' => $agent_id,
                    'accounting_period' => $accounting_period,
                    'dashboard_activity_id' => $dashboard_activity_id,
                    'client_activity_id' => $client_activity_id,
                    'prerequisite_dependency' => $prerequisite_dependency,
                    'client_detailed_activity' => $client_detailed_activity,
                    'poc' => $poc,
                    'go_live_date' => $go_live_date,
                    'frequency' => $frequency,
                    'due_date' => $due_date,
                    'estimated_handling_time' => $estimated_handling_time,
                    'status' => $status,
                    'status_date' => $status_date,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'actual_handling_time' => $actual_handling_time,
                    'volume' => $volume,
                    'remarks' => $remarks,
                    'dtp_link' => $dtp_link,
                    'training_recording_link' => $training_recording_link,
                    'created_by' => $created_by,
                ]
            );
        }
    }

    public function getErrors()
    {
        return $this->has_error;
    }

    public function rules(): array
    {
        return [
            '*.cluster' => ['required'],
            '*.client' => ['required'],
            '*.employee_number' => ['required'],
            '*.employee_name' => ['required'],
            '*.accounting_period' => ['required'],
            '*.dashboard_activity' => ['required'],
            '*.client_activity' => ['required'],
            '*.client_detailed_activity' => ['required'],
            '*.poc' => ['required'],
            '*.frequency' => ['required'],
            '*.due_date' => ['required'],
            '*.estimated_handling_time' => ['required'],
        ];
    }
}
