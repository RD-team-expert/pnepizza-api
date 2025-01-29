<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image_url' => 'nullable|url',
            'street' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'lc_url' => 'boolean',
        ]);

        $location = Location::create($request->all());
        return response()->json($location, 201);
    }

    // Read All Locations
    public function index(Request $request)
    {
        $query = Location::query();

        // Optional filters
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('street', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $locations = $query->get();
        return response()->json($locations);
    }

    // Read Single Location
    public function show($id)
    {
        $location = Location::findOrFail($id);
        return response()->json($location);
    }

    // Update Location
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string',
            'image_url' => 'nullable|url',
            'street' => 'sometimes|string',
            'city' => 'sometimes|string',
            'state' => 'sometimes|string',
            'zip' => 'sometimes|string',
            'description' => 'nullable|string',
            'status' => 'sometimes|boolean',
        ]);

        $location->update($request->all());
        return response()->json($location);
    }

    // Delete Location
    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();
        return response()->json(null, 204);
    }
}



