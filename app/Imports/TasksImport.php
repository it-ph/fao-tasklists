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

        $shift_date = $row['shift_date'];
        $description = $row['description'];

        $status = 'Not Started';
        $start_date = null;
        $end_date = null;
        $actual_handling_time = null;
        $volume = null;
        $remarks = null;

        $created_by = Auth::id();

        if($ctr_error <= 0)
        {
            Task::updateOrCreate(
                [
                    'agent_id' => $agent_id,
                    'cluster_id' => $cluster_id,
                    'client_id' => $client_id,
                    'shift_date' => $shift_date,
                    'dashboard_activity_id' => $dashboard_activity_id,
                    'client_activity_id' => $client_activity_id,
                    'description' => $description,
                    'status' => $status,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'actual_handling_time' => $actual_handling_time,
                    'volume' => $volume,
                    'remarks' => $remarks,
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
            '*.email_address' => ['required'],
            '*.employee_name' => ['required'],
            '*.shift_date' => ['required'],
            '*.dashboard_activity' => ['required'],
            '*.client_activity' => ['required'],
            '*.description' => ['required'],
        ];
    }
}
