<?php

namespace App\Imports;

use App\Models\Cluster;
use App\Models\DashboardActivity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DashboardActivityImport implements ToModel, WithHeadingRow,WithValidation,SkipsEmptyRows
{
    private $has_error = array();
    private $row_number = 1;
    public function model(array $row)
    {
        $ctr_error = 0;
        array_push($this->has_error, "Something went wrong, Please check all entries that you have encoded.");

        $user_cluster = Auth::user()->thepermisssion->cluster_id;
        $cluster = Cluster::where('name', $row['cluster_name'])->select('id','name')->first();

        $this->row_number += 1;
        if($cluster)
        {
            $is_cluster_member = $user_cluster == $cluster->id ? true : false;
            if ($is_cluster_member || Auth::user()->isAdmin())
            {
                $cluster_id = $cluster->id;
            }
            else
            {
                $ctr_error += 1;
                array_push($this->has_error, " Check Cell B".$this->row_number.", "."You are not allowed to upload dashboard activity to ".$row['cluster_name'].".");
            }
        }
        else
        {
            $ctr_error += 1;
            array_push($this->has_error, " Check Cell B".$this->row_number.", "."Cluster: ".$row['cluster_name']." does not exist.");
        }

        if($ctr_error <= 0)
        {
            DashboardActivity::updateOrCreate(
                [
                    'name' => $row['dashboard_activity'],
                    'cluster_id' => $cluster_id
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
            '*.dashboard_activity' => ['required'],
            '*.cluster_name' => ['required']
        ];
    }
}
