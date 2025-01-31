<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Job",
 *     required={"job_title", "min_salary", "max_salary", "city", "state", "job_type", "job_description"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="job_title", type="string", example="Software Engineer"),
 *     @OA\Property(property="min_salary", type="number", format="float", example=50000),
 *     @OA\Property(property="max_salary", type="number", format="float", example=70000),
 *     @OA\Property(property="city", type="string", example="Los Angeles"),
 *     @OA\Property(property="state", type="string", example="CA"),
 *     @OA\Property(property="job_type", type="string", example="Full-time"),
 *     @OA\Property(property="job_description", type="string", example="Develop and maintain software applications."),
 *     @OA\Property(property="status", type="string", example="active")
 * )
 */

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
