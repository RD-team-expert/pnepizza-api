<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'user_id',
        'file_name',
        'file_path',
        'thumbnail_path',
        'file_size',
        'mime_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
