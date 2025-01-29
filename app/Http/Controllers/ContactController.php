<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // GET /api/contacts (Read all contacts)
    public function index(Request $request)
    {
        $query = Contact::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        // Full-text search on name, email, or message
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('message', 'like', "%$search%");
            });
        }

        $contacts = $query->get();
        return response()->json($contacts);
    }

    // POST /api/contacts (Create a new contact)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
            'contact_via_email' => 'nullable|boolean',
            'contact_via_phone' => 'nullable|boolean',
            'status' => 'nullable|string|in:pending,completed',
            'priority' => 'nullable|string|in:low,medium,high',
        ]);

        $contact = Contact::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'message' => $request->input('message'),
            'contact_via_email' => $request->input('contact_via_email', false), // Default to false
            'contact_via_phone' => $request->input('contact_via_phone', false), // Default to false
            'status' => $request->input('status', 'pending'), // Default to 'pending'
            'priority' => $request->input('priority', 'medium'), // Default to 'medium'
        ]);

        return response()->json($contact, 201);
    }

    // GET /api/contacts/{id} (Read a single contact)
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return response()->json($contact);
    }

    // PUT /api/contacts/{id} (Update a contact)
    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:contacts,email,' . $contact->id,
            'phone' => 'nullable|string|max:20',
            'message' => 'sometimes|string',
            'contact_via_email' => 'nullable|boolean',
            'contact_via_phone' => 'nullable|boolean',
            'status' => 'nullable|string|in:pending,completed',
            'priority' => 'nullable|string|in:low,medium,high',
        ]);

        $contact->update($request->all());
        return response()->json($contact);
    }

    // DELETE /api/contacts/{id} (Delete a contact)
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return response()->json(null, 204);
    }
}
