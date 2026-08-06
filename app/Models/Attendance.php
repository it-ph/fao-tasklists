<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory;
    use Softdeletes;

    protected $table = 'attendances';
    protected $guarded = [];
    protected $dates = ['shift_date', 'clock_in', 'clock_out', 'created_at', 'updated_at', 'deleted_at'];

    public function theagent()
    {
        return $this->belongsTo(User::class, 'agent_id', 'id')->withTrashed();
    }
}
