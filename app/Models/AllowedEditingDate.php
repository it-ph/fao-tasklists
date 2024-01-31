<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowedEditingDate extends Model
{
    use HasFactory;
    protected $table='allowed_editing_date';
    protected $guarded = [];
    protected $dates = ['created_at', 'allowed_date_from', 'allowed_date_to'];
}
