<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Milestone",
 *     required={"date", "title"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="date", type="string", format="date", example="2025-02-01"),
 *     @OA\Property(property="title", type="string", example="Project Kickoff"),
 *     @OA\Property(property="description", type="string", example="Initial phase of the project."),
 *     @OA\Property(property="status", type="string", enum={"pending", "completed", "in_progress"}, example="pending"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Milestone extends Model
{
    protected $fillable = [
        'date',
        'title',
        'description',
        'status',
    ];

}
