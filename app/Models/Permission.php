<?php

namespace App\Models;

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

    public function scopeTLPermission($query)
    {
        return $query->where('tl_id',Auth::user()->emp_id)->where('cluster_id',Auth::user()->thepermisssion->cluster_id)->orwhere('user_id',Auth::user()->emp_id);
    }

    public function scopeOMPermission($query)
    {
        return $query->where('cluster_id',Auth::user()->thepermisssion->cluster_id);
    }

    public function theuser()
    {
        return $this->belongsTo(User::class, 'user_id', 'emp_id');
    }

    public function thecluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }

    public function theclient()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function theclientactivities()
    {
        return $this->hasMany(ClientActivity::class, 'agent_id', 'user_id');
    }

    public function thetl()
    {
        return $this->belongsTo(Permission::class, 'tl_id', 'user_id');
    }

    public function theom()
    {
        return $this->belongsTo(Permission::class, 'om_id', 'user_id');
    }

    public function theclients()
    {
        return $this->hasMany(UserClient::class, 'client_id', 'user_id');
    }
}
