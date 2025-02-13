<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Services\NotificationService;
use Illuminate\Http\Request;


class FeedbackController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/feedback",
     *     summary="Create customer feedback",
     *     tags={"Feedback"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Feedback")
     *     ),
     *     @OA\Response(response=201, description="Feedback created successfully"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function store(Request $request , NotificationService $notificationService)
    {
        try {
        $request->validate([
            'customer_name' => 'required|string',
            'rating' => 'nullable|integer|between:1,5',
            'comment' => 'string',
            'email' => 'required|string|email',
        ]);

        $feedback = Feedback::create([
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'comment' => $request->comment,
                'email' => $request->email,
            'status' => 'Pending', // Default status
        ]);

        $notificationService->notifyAdmin('A new feedback has been created: ' . $feedback->customer_name);


            return response()->json($feedback, 201);
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/feedback",
     *     summary="Get published feedback",
     *     tags={"Feedback"},
     *     @OA\Parameter(
     *         name="location_id",
     *         in="query",
     *         description="Filter feedback by location ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of published feedback",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Feedback"))
     *     ),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
        $query = Feedback::where('status', 'Published');


        return response()->json($query->get());

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/feedback/admin",
     *     summary="Get all feedback (Admin only)",
     *     tags={"Feedback"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter feedback by status (Pending, Published, Archived)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="List of feedback"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function adminIndex(Request $request)
    {
        try {

        $query = Feedback::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->get());

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/feedback/{id}",
     *     summary="Update feedback status or comment (Admin only)",
     *     tags={"Feedback"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Feedback ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Feedback")
     *     ),
     *     @OA\Response(response=200, description="Feedback updated"),
     *     @OA\Response(response=404, description="Feedback not found"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {

        $feedback = Feedback::findOrFail($id);

        $request->validate([
            'status' => 'sometimes|in:Pending,Published,Archived',
            'comment' => 'sometimes|string',
        ]);

        $feedback->update($request->only(['status', 'comment']));
        return response()->json($feedback);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/feedback/{id}",
     *     summary="Delete feedback (Admin only)",
     *     tags={"Feedback"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Feedback ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Feedback deleted"),
     *     @OA\Response(response=404, description="Feedback not found"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function destroy($id)
    {
        try {

        Feedback::findOrFail($id)->delete();
        return response()->json(null, 204);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }
}
