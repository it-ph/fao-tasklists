<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskLog extends Model
{
    use HasFactory;

    protected $table = 'task_logs';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];

    public function thetask()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function thecreatedby()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
