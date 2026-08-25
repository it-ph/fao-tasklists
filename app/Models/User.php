<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    // protected $connection = 'mysql2';
    protected $table = 'users';
    protected $dates = ['two_factor_expires_at','deleted_at','created_at','updated_at'];

    // protected $casts = [
    //     'shift_date' => 'date',
    //     'clock_in'   => 'datetime',
    //     'clock_out'  => 'datetime',
    // ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function theattendances()
    {
        return $this->hasMany(Attendance::class, 'agent_id');
    }

    public function todaysAttendance()
    {
        return $this->hasOne(Attendance::class, 'agent_id')
            ->whereDate('shift_date', today()); // Filters strictly for today's calendar date
    }

    public function scopeAgentPermission($query)
    {
        return $query->where('id',auth()->user()->id);
    }

    public function scopeTLPermission($query)
    {
        return $query->where('tl_id',auth()->user()->id)->where('cluster_id',auth()->user()->cluster_id)->orwhere('id',auth()->user()->id);
    }

    public function scopeOMPermission($query)
    {
        return $query->where('cluster_id',auth()->user()->cluster_id);
    }

    public function thecluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id')->withTrashed();
    }

    public function theclient()
    {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }

    public function theclientactivities()
    {
        return $this->hasMany(ClientActivity::class, 'agent_id', 'id')->withTrashed();
    }

    public function thetl()
    {
        return $this->belongsTo(User::class, 'tl_id', 'id')->withTrashed();
    }

    public function theom()
    {
        return $this->belongsTo(User::class, 'om_id', 'id')->withTrashed();
    }

    public function thetasks()
    {
        return $this->hasMany(Task::class, 'agent_id', 'id');
    }

    public function thetaskassignments()
    {
        return $this->hasMany(TaskAssignment::class, 'agent_id', 'id');
    }

    public function theactivetask()
    {
        return $this->hasOne(Task::class, 'agent_id')->where('status', 'In Progress');
    }

    public function theactiveassignment()
    {
        return $this->hasOne(TaskAssignment::class, 'agent_id')->where('status', 'In Progress');
    }

    public function hasActiveTask()
    {
        // $hasActiveTask = Task::query()
        //     ->where('agent_id', $this->id)
        //     ->where('status', 'In Progress')
        //     ->count();

        // $hasActiveTask = $hasActiveTask ? true : false;
        // return $hasActiveTask;

        // check if user has active task in both My Task and Task Assigned
        if (Task::where('agent_id', $this->id)->where('status', 'In Progress')->exists()) {
            return true;
        }

        return TaskAssignment::where('agent_id', $this->id)->where('status', 'In Progress')->exists();
    }

    public function hasClockedInToday()
    {
        return $this->todaysAttendance()
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->exists();
    }

    public function isStatusActive()
    {
        $hasPermission = User::query()
            ->where('id', $this->id)
            ->first();

        if($this->status  == 'active' && $hasPermission)
        {
            return true;
        }
        return false;
    }

    /**
     *  START OF USER PERMISSIONS
     */

    // accountant
    public function isAccountant(): bool
    {
        return $this->permission === 'accountant';
    }

    // admin
    public function isAdmin(): bool
    {
        return in_array($this->permission, ['superadmin', 'admin']);
    }

    // Team Leader
    public function isTeamLeader(): bool
    {
        return in_array($this->permission, ['superadmin', 'team leader']);
    }

    // Operations Manager
    public function isOperationsManager(): bool
    {
        return in_array($this->permission, ['superadmin', 'operations manager']);
    }

    // admin or team leader
    public function isTeamLeaderOrAdmin(): bool
    {
        return in_array($this->permission, ['superadmin', 'admin', 'team leader']);
    }

    // admin or operations manager
    public function isOperationsManagerOrAdmin(): bool
    {
        return in_array($this->permission, ['superadmin', 'admin', 'operations manager']);
    }

    // admin, team leader or operations manager
    public function isTLOMOrAdmin(): bool
    {
        return in_array($this->permission, ['superadmin', 'admin', 'team leader', 'operations manager']);
    }

    /**
     * END OF USER PERMISSIONS
     */


    /**
     * Generate 6 digits MFA code for the User
     */
    public function generateTwoFactorCode()
    {
        $this->timestamps = false; //Dont update the 'updated_at' field yet

        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();
    }

    /**
     * Reset the MFA code generated earlier
     */
    public function resetTwoFactorCode()
    {
        $this->timestamps = false; //Dont update the 'updated_at' field yet

        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    public function is2FApassed()
    {
        $user = User::where('email', Auth::user()->email)->first();

        if($user->two_factor_code == NULL && $user->two_factor_expires_at == NULL)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
