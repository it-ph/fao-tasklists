<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cluster extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'clusters';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
