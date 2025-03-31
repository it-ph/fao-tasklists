<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskPause extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'task_pauses';
    protected $guarded = [];
    protected $dates = ['start','end','create_at','updated_at','deleted_at'];

    public function thetask()
    {
        return $this->belongsTo(Task::class,'task_id')->withTrashed();
    }

    public function thecreatedby()
    {
        return $this->belongsTo(User::class,'created_by')->withTrashed();
    }
}