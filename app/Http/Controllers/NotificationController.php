<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/notifications",
     *     summary="Fetch all notifications for admin users",
     *     tags={"Notifications"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="type", type="string", example="App\Notifications\OrderShipped"),
     *                 @OA\Property(property="data", type="object", example={"order_id": 123}),
     *                 @OA\Property(property="read_at", type="string", format="date-time", example="2023-10-01T12:00:00Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-01T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-01T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="No admin users found"),
     *     @OA\Response(response=403, description="Unauthorized access")
     * )
     */
    public function index(Request $request)
    {
        try {
            // Ensure the authenticated user is an admin
            $user = Auth::user();


            if (!$user || !$user->hasRole('Admin')) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Fetch notifications for the authenticated admin
            $notifications = $user->notifications;

            if ($notifications->isEmpty()) {
                return response()->json(['message' => 'No notifications found'], 404);
            }

            return response()->json($notifications);


        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/notifications/{id}/mark-as-read",
     *     summary="Mark a notification as read",
     *     tags={"Notifications"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Notification ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Notification marked as read"),
     *     @OA\Response(response=403, description="Unauthorized access"),
     *     @OA\Response(response=404, description="Notification not found")
     * )
     */

    public function markAsRead(Request $request, $id)
    {
        try {


            // Ensure the authenticated user is an admin
            $user = Auth::user();

            if (!$user || !$user->hasRole('Admin')) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Find the notification by ID and ensure it belongs to the authenticated admin
            $notification = $user->notifications()->findOrFail($id);

            // Mark the notification as read
            $notification->markAsRead();

            return response()->json(['message' => 'Notification marked as read']);


        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }
}
