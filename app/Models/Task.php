<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'tasks';
    protected $guarded = [];
    protected $dates = ['go_live_date', 'status_date', 'start_date', 'end_date', 'created_at', 'updated_at', 'deleted_at'];

    public function scopeTLPermission($query)
    {
        return $query->whereHas('thepermission', function ($q){
                $q->where('tl_id',Auth::id());
            })
            ->where('cluster_id',Auth::user()->thepermisssion->cluster_id)
            ->orwhere('agent_id',Auth::id());
    }

    public function scopeOMPermission($query)
    {
        return $query->where('cluster_id',Auth::user()->thepermisssion->cluster_id);
    }

    public function thecluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }

    public function theclient()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function theagent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function thepermission()
    {
        return $this->hasOne(Permission::class, 'user_id', 'agent_id');
    }

    public function thedashboardactivity()
    {
        return $this->belongsTo(DashboardActivity::class, 'dashboard_activity_id');
    }

    public function theclientactivity()
    {
        return $this->belongsTo(ClientActivity::class, 'client_activity_id');
    }

    public function thecreatedby()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function thetasklogs()
    {
        return $this->hasMany(TaskLog::class, 'task_id');
    }
}
