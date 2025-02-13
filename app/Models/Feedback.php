<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Feedback",
 *     required={"customer_name", "comment", "location_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="customer_name", type="string", example="John Doe"),
 *     @OA\Property(property="rating", type="integer", example=5, minimum=1, maximum=5),
 *     @OA\Property(property="comment", type="string", example="Great service and friendly staff."),
 *     @OA\Property(property="location_id", type="integer", example=3),
 *     @OA\Property(property="status", type="string", example="Pending"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class Feedback extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_name', 'rating', 'comment', 'status', 'email'
    ];


}
