<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="TeamMember",
 *     required={"name", "role"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="role", type="string", example="Project Manager"),
 *     @OA\Property(property="profile_image", type="string", nullable=true, example="https://example.com/avatar.jpg"),
 *     @OA\Property(property="bio", type="string", nullable=true, example="Experienced project manager with a focus on agile methodologies."),
 *     @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'role',
        'profile_image',
        'description',
    ];
}
