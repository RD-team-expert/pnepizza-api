<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'image_url' => 'nullable|url',
            'description' => 'required|string',
            'datetime' => 'required|date',
            'location' => 'required|string',
            'capacity' => 'required|integer',
            'status' => 'sometimes|string',
        ]);

        $event = Event::create($request->all());
        return response()->json($event, 201);
    }

    // Read All Events
    public function index(Request $request)
    {
        $query = Event::query();

        // Optional filters
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('datetime', [$request->date_from, $request->date_to]);
        }

        $events = $query->get();
        return response()->json($events);
    }

    // Read Single Event
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return response()->json($event);
    }

    // Update Event
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string',
            'image_url' => 'nullable|url',
            'description' => 'sometimes|string',
            'datetime' => 'sometimes|date',
            'location' => 'sometimes|string',
            'capacity' => 'sometimes|integer',
            'status' => 'sometimes|string',
        ]);

        $event->update($request->all());
        return response()->json($event);
    }

    // Delete Event
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(null, 204);
    }
}
