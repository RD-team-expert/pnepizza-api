<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'contact_via_email',
        'contact_via_phone',
        'status',
        'priority',
    ];
}
