<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Acquisition",
 *     required={"name", "email", "city", "state"},
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="phone", type="string", example="1234567890"),
 *     @OA\Property(property="city", type="string", example="Los Angeles"),
 *     @OA\Property(property="state", type="string", example="CA"),
 *     @OA\Property(property="status", type="string", example="New"),
 *     @OA\Property(property="priority", type="string", example="High"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Acquisition extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'info'

    ];
}
