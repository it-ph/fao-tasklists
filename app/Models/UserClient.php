<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserClient extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'user_clients';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function theuser()
    {
        return $this->belongsTo(User::class, 'emp_id');
    }

    public function theclient()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
