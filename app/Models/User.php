<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // protected $connection = 'mysql2';
    protected $table = 'users';
    protected $dates = ['two_facor_expires_at'];

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

    function thepermisssion()
    {
        return $this->hasOne(Permission::class, 'user_id', 'emp_id');
    }

    public function theclientactivities()
    {
        return $this->hasMany(ClientActivity::class, 'agent_id', 'emp_id');
    }

    public function thetasks()
    {
        return $this->hasMany(Task::class, 'agent_id', 'emp_id');
    }

    public function hasActiveTask()
    {
        $hasActiveTask = Task::query()
            ->where('agent_id', $this->emp_id)
            ->where('status', 'In Progress')
            ->count();

        $hasActiveTask = $hasActiveTask ? true : false;

        return $hasActiveTask;
    }

    public function isStatusActive()
    {
        $hasPermission = User::query()
            ->where('emp_id', $this->emp_id)
            ->first();

        if($this->employment_status  == 'active' && $hasPermission)
        {
            return true;
        }
        return false;
    }

    /**
     *  START OF USER PERMISSIONS
     */

    // accountant
    public function isAccountant()
    {
        $permission = 'accountant';
        $hasPermission = Permission::query()
            ->whereIn('permission',[
                $permission
            ])
            ->where('user_id',$this->emp_id)
            ->first();

        if($hasPermission)
        {
            return true;
        }

        return false;
    }

    // admin
    public function isAdmin()
    {
        $permission = 'admin';
        $hasPermission = Permission::query()
            ->whereIn('permission',[
                'superadmin',
                $permission
            ])
            ->where('user_id',$this->emp_id)
            ->first();

        if($hasPermission)
        {
            return true;
        }

        return false;
    }

    // Team Leader
    public function isTeamLeader()
    {
        $permission = 'team leader';
        $hasPermission = Permission::query()
            ->whereIn('permission',[
                'superadmin',
                $permission
            ])
            ->where('user_id',$this->emp_id)
            ->first();

        if($hasPermission)
        {
            return true;
        }

        return false;
    }

    // Operations Manager
    public function isOperationsManager()
    {
        $permission = 'operations manager';
        $hasPermission = Permission::query()
            ->whereIn('permission',[
                'superadmin',
                $permission
            ])
            ->where('user_id',$this->emp_id)
            ->first();

        if($hasPermission)
        {
            return true;
        }

        return false;
    }

    // admin or team leader
    public function isTeamLeaderOrAdmin()
    {
        $permission = 'team leader';
        $hasPermission = Permission::query()
            ->whereIn('permission',[
                'superadmin',
                'admin',
                $permission
            ])
            ->where('user_id',$this->emp_id)
            ->first();

        if($hasPermission)
        {
            return true;
        }

        return false;
    }

    // admin or operations manager
    public function isOperationsManagerOrAdmin()
    {
        $permission = 'operations manager';
        $hasPermission = Permission::query()
            ->whereIn('permission',[
                'superadmin',
                'admin',
                $permission
            ])
            ->where('user_id',$this->emp_id)
            ->first();

        if($hasPermission)
        {
            return true;
        }

        return false;
    }

    // admin, team leader or operations manager
    public function isTLOMOrAdmin()
    {
        $tl = 'team leader';
        $om = 'operations manager';
        $hasPermission = Permission::query()
            ->whereIn('permission',[
                'superadmin',
                'admin',
                $tl,
                $om
            ])
            ->where('user_id',$this->emp_id)
            ->first();

        if($hasPermission)
        {
            return true;
        }

        return false;
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
