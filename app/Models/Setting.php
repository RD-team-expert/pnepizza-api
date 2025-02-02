<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'website_title',
        'keywords',
        'description',
        'og_title',
        'og_image_url',
        'og_description',
    ];
}
