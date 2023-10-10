<?php

namespace App\Services;

use App\Models\Cluster;
use Illuminate\Support\Facades\Auth;

class ClustersServices
{
    public function load()
    {
        $datastorage = [];
        $clusters = Cluster::all();

        foreach($clusters as $value) {
            $name = $value->name;
            $updated_at = date("m/d/Y h:i:s a",strtotime($value->updated_at));
            $action ='<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit User" onclick=CLUSTER.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" title="Delete User" onclick=CLUSTER.destroy('.$value->id.')><i class="fas fa-times"></i></button>';

            $datastorage[] = [
                'id' => $value->id,
                'name' => $name,
                'updated_at' => $updated_at,
                'action' => $action,
            ];
        }

        return $datastorage;
    }
}
