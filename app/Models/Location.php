<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Location",
 *     required={"name", "street", "city", "state", "zip"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Downtown Office"),
 *     @OA\Property(property="image_url", type="string", example="https://example.com/location-image.jpg"),
 *     @OA\Property(property="street", type="string", example="123 Main St"),
 *     @OA\Property(property="city", type="string", example="Los Angeles"),
 *     @OA\Property(property="state", type="string", example="CA"),
 *     @OA\Property(property="zip", type="string", example="90001"),
 *     @OA\Property(property="description", type="string", example="Main headquarters"),
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="lc_url", type="boolean", example=false),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_url',
        'street',
        'city',
        'state',
        'zip',
        'description',
        'status',
        'lc_url',
        'lc_number',
        'latitude',
        'longitude'
    ];


}
