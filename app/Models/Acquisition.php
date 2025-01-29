<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acquisition extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
        'state',
        'status',
        'priority',
    ];
}
