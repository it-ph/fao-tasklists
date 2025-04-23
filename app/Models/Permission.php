<?php

namespace App\Models;

use Dcblogdev\MsGraph\Resources\Tasks;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'permissions';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at','shift_date'];

    public function scopeAgentPermission($query)
    {
        return $query->where('user_id',auth()->user()->id);
    }

    public function scopeTLPermission($query)
    {
        return $query->where('tl_id',auth()->user()->id)->where('cluster_id',auth()->user()->thepermisssion->cluster_id)->orwhere('user_id',auth()->user()->id);
    }

    public function scopeOMPermission($query)
    {
        return $query->where('cluster_id',auth()->user()->thepermisssion->cluster_id);
    }

    public function theuser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getFullNameAttribute()
    {
        return $this->theuser->fullname . ' ' . $this->theuser->last_name;
    }

    public function thecluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id')->withTrashed();;
    }

    public function theclient()
    {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();;
    }

    public function theclientactivities()
    {
        return $this->hasMany(ClientActivity::class, 'agent_id', 'user_id')->withTrashed();;
    }

    public function thetl()
    {
        return $this->belongsTo(Permission::class, 'tl_id', 'user_id');
    }

    public function getTLFullNameAttribute()
    {
        return $this->thetl ? $this->thetl->fullname . ' ' . $this->thetl->last_name : null;
    }

    public function theom()
    {
        return $this->belongsTo(Permission::class, 'om_id', 'user_id');
    }

    public function getOMFullNameAttribute()
    {
        return $this->theom ? $this->theom->fullname . ' ' . $this->theom->last_name : null;
    }

    public function theclients()
    {
        return $this->hasMany(UserClient::class, 'client_id', 'user_id');
    }

    public function thetasks()
    {
        return $this->hasMany(Task::class, 'agent_id', 'user_id');
    }
}
