<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'website_title',
        'keywords',
        'description',
        'Google_Maps_API_Key',
        'Google_Analytics_ID',
        'facebook_url',
        'instagram_url',
        'logo_image',
    ];
}
