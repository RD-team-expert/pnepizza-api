<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    // GET /api/team-members (List all team members)
    public function index(Request $request)
    {
        $query = TeamMember::query();

        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->input('role'));
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Search by name or bio
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('bio', 'like', "%$search%");
            });
        }

        $teamMembers = $query->get();
        return response()->json($teamMembers);
    }

    // POST /api/team-members (Create a new team member)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'profile_image' => 'nullable|string', // Assuming this is a file path or URL
            'bio' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive', // Example statuses
        ]);

        $teamMember = TeamMember::create([
            'name' => $request->input('name'),
            'role' => $request->input('role'),
            'profile_image' => $request->input('profile_image'),
            'bio' => $request->input('bio'),
            'status' => $request->input('status', 'active'), // Default to 'active'
        ]);

        return response()->json($teamMember, 201);
    }

    // GET /api/team-members/{id} (Get a single team member)
    public function show($id)
    {
        $teamMember = TeamMember::findOrFail($id);
        return response()->json($teamMember);
    }

    // PUT /api/team-members/{id} (Update a team member)
    public function update(Request $request, $id)
    {
        $teamMember = TeamMember::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'role' => 'sometimes|string|max:255',
            'profile_image' => 'nullable|string',
            'bio' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $teamMember->update($request->all());
        return response()->json($teamMember);
    }

    // DELETE /api/team-members/{id} (Delete a team member)
    public function destroy($id)
    {
        $teamMember = TeamMember::findOrFail($id);
        $teamMember->delete();
        return response()->json(null, 204);
    }
}
