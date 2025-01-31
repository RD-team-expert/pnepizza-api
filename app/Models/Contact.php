<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Contact",
 *     required={"name", "email", "message"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *     @OA\Property(property="phone", type="string", example="1234567890"),
 *     @OA\Property(property="message", type="string", example="Hello, I have a question."),
 *     @OA\Property(property="contact_via_email", type="boolean", example=true),
 *     @OA\Property(property="contact_via_phone", type="boolean", example=false),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="priority", type="string", example="medium"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'contact_via_email',
        'contact_via_phone',
        'status',
        'priority',
    ];

}
