<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChangeRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'change_requests';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

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
            ->orwhere('created_by',$user->id);
    }

    public function scopeAccountantPermission($query)
    {
        return $query->where('created_by',auth()->user()->id);
    }

    public function thecreatedby()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function thechangedby()
    {
        return $this->belongsTo(User::class, 'changed_by')->withTrashed();
    }

    public function thepermission()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function thetask()
    {
        return $this->belongsTo(Task::class, 'task_id')->withTrashed();
    }

    public function thecluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id')->withTrashed();
    }
}
