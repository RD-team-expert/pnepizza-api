<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Event",
 *     required={"title", "description", "datetime", "location", "capacity"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Tech Conference 2025"),
 *     @OA\Property(property="image_url", type="string", example="https://example.com/event-image.jpg"),
 *     @OA\Property(property="description", type="string", example="A conference about the latest in technology."),
 *     @OA\Property(property="datetime", type="string", format="date-time", example="2025-01-30 15:29:58"),
 *     @OA\Property(property="location", type="string", example="Los Angeles Convention Center"),
 *     @OA\Property(property="capacity", type="integer", example=500),
 *     @OA\Property(property="status", type="string", example="active"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'image_url', 'description', 'datetime', 'location', 'capacity', 'status'
    ];
}
