<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_url',
        'street',
        'city',
        'state',
        'zip',
        'description',
        'status',
        'lc_url'
    ];

    public function Feedback()
    {
        return $this->hasMany(Feedback::class);
    }
}
