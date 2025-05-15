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

    public function scopeUserPermission($query)
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

    public function thetask()
    {
        return $this->belonsTo(Task::class, 'task_id')->withTrashed();
    }

    public function thecluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id')->withTrashed();
    }
}
