<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // Public: Create Feedback
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'rating' => 'nullable|integer|between:1,5',
            'comment' => 'required|string',
            'location_id' => 'required|exists:locations,id',
        ]);

        $feedback = Feedback::create([
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'location_id' => $request->location_id,
            'status' => 'Pending', // Default status
        ]);

        return response()->json($feedback, 201);
    }

    // Public: Read Published Feedback
    public function index(Request $request)
    {
        $query = Feedback::where('status', 'Published');

        if ($request->has('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        $feedback = $query->get();
        return response()->json($feedback);
    }

    // Admin: Read All Feedback (with filters)
    public function adminIndex(Request $request)
    {
        $query = Feedback::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $feedback = $query->get();
        return response()->json($feedback);
    }

    // Admin: Update Feedback Status/Comment
    public function update(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);

        $request->validate([
            'status' => 'sometimes|in:Pending,Published,Archived',
            'comment' => 'sometimes|string',
        ]);

        $feedback->update($request->only(['status', 'comment']));
        return response()->json($feedback);
    }

    // Admin: Delete Feedback
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();
        return response()->json(null, 204);
    }
}
