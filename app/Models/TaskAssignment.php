<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskAssignment extends Model
{
    use HasFactory;
    use Softdeletes;

    protected $table = "task_assignments";
    protected $guarded = [];
    protected $dates = ['applicable_month', 'schedule', 'start_date', 'end_date', 'created_at', 'updated_at', 'deleted_at'];

    public function scopeOMPermission($query)
    {
        return $query->where('cluster_id',auth()->user()->cluster_id);
    }

    public function scopeTLPermission($query)
    {
        $user = auth()->user();
        return $query->whereHas('thepermission', function ($q) use ($user){
                $q->where('tl_id',$user->id);
            })
            ->where('cluster_id',$user->cluster_id)
            ->orwhere('agent_id',$user->id);
    }

    public function scopeAccountantPermission($query)
    {
        return $query->where('agent_id',auth()->user()->id);
    }

    public function scopeTaskFunction($query)
    {
        return $query->whereHas('theclientactivity', function ($q){
                $q->where('function','<>','Personiv Admin');
            });
    }

    public function thecluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id')->withTrashed();
    }

    public function theclient()
    {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }

    public function theagent()
    {
        return $this->belongsTo(User::class, 'agent_id', 'id')->withTrashed();
    }

    public function thepermission()
    {
        return $this->hasOne(User::class, 'id', 'agent_id')->withTrashed();
    }

    public function thecreatedby()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
}
