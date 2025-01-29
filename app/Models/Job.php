<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'job_title',
        'min_salary',
        'max_salary',
        'city',
        'state',
        'job_type',
        'job_description',
        'indeed_link',
        'workstream_link',
        'status',
    ];

}
