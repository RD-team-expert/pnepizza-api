<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_name', 'rating', 'comment', 'location_id', 'status'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
