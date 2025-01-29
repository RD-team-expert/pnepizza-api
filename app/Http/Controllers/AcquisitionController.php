<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Acquisition;
use Illuminate\Http\Request;

class AcquisitionController extends Controller
{
    // GET /api/acquisitions (Read all acquisitions)
    public function index(Request $request)
    {
        $query = Acquisition::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        // Full-text search on name, email, city, or state
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('city', 'like', "%$search%")
                    ->orWhere('state', 'like', "%$search%");
            });
        }

        $acquisitions = $query->get();
        return response()->json($acquisitions);
    }

    // POST /api/acquisitions (Create a new acquisition)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:acquisitions,email',
            'phone' => 'nullable|string|max:20',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'status' => 'nullable|string|in:New,In Review,Contacted,Closed',
            'priority' => 'nullable|string|in:High,Medium,Low',
        ]);

        $acquisition = Acquisition::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'status' => $request->input('status', 'New'), // Default to 'New'
            'priority' => $request->input('priority', 'Medium'), // Default to 'Medium'
        ]);

        return response()->json($acquisition, 201);
    }

    // GET /api/acquisitions/{id} (Read a single acquisition)
    public function show($id)
    {
        $acquisition = Acquisition::findOrFail($id);
        return response()->json($acquisition);
    }

    // PUT /api/acquisitions/{id} (Update an acquisition)
    public function update(Request $request, $id)
    {
        $acquisition = Acquisition::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:acquisitions,email,' . $acquisition->id,
            'phone' => 'nullable|string|max:20',
            'city' => 'sometimes|string|max:100',
            'state' => 'sometimes|string|size:2',
            'status' => 'nullable|string|in:New,In Review,Contacted,Closed',
            'priority' => 'nullable|string|in:High,Medium,Low',
        ]);

        $acquisition->update($request->all());
        return response()->json($acquisition);
    }

    // DELETE /api/acquisitions/{id} (Delete an acquisition)
    public function destroy($id)
    {
        $acquisition = Acquisition::findOrFail($id);
        $acquisition->delete();
        return response()->json(null, 204);
    }
}
