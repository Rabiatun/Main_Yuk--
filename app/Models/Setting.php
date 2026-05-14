<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['sport_center_name', 'address', 'phone', 'email'];
}
