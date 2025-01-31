<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Media",
 *     required={"user_id", "file_name", "file_path", "thumbnail_path", "file_size", "mime_type"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=5),
 *     @OA\Property(property="file_name", type="string", example="image.jpg"),
 *     @OA\Property(property="file_path", type="string", example="/storage/uploads/image.jpg"),
 *     @OA\Property(property="thumbnail_path", type="string", example="/storage/uploads/thumbnails/thumb_image.jpg"),
 *     @OA\Property(property="file_size", type="integer", example=204800),
 *     @OA\Property(property="mime_type", type="string", example="image/jpeg"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Media extends Model
{
    protected $fillable = [
        'user_id',
        'file_name',
        'file_path',
        'thumbnail_path',
        'file_size',
        'mime_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
