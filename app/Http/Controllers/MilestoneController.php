<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    // GET /api/milestones (Read all milestones)
    public function index(Request $request)
    {
        $query = Milestone::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('date', '>=', $request->input('start_date'));
        }
        if ($request->has('end_date')) {
            $query->where('date', '<=', $request->input('end_date'));
        }

        // Full-text search on title or description
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        $milestones = $query->get();
        return response()->json($milestones);
    }

    // POST /api/milestones (Create a new milestone)
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:pending,completed,in_progress', // Example statuses
        ]);

        $milestone = Milestone::create([
            'date' => $request->input('date'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status', 'pending'), // Default to 'pending'
        ]);

        return response()->json($milestone, 201);
    }

    // GET /api/milestones/{id} (Read a single milestone)
    public function show($id)
    {
        $milestone = Milestone::findOrFail($id);
        return response()->json($milestone);
    }

    // PUT /api/milestones/{id} (Update a milestone)
    public function update(Request $request, $id)
    {
        $milestone = Milestone::findOrFail($id);

        $request->validate([
            'date' => 'sometimes|date',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:pending,completed,in_progress',
        ]);

        $milestone->update($request->all());
        return response()->json($milestone);
    }

    // DELETE /api/milestones/{id} (Delete a milestone)
    public function destroy($id)
    {
        $milestone = Milestone::findOrFail($id);
        $milestone->delete();
        return response()->json(null, 204);
    }
}
