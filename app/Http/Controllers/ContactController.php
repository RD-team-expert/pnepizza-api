<?php

namespace App\Http\Controllers;

use App\Events\ContactCreated;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Services\NotificationService;
use Illuminate\Http\Request;


class ContactController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/contacts",
     *     summary="Get all contacts",
     *     tags={"Contacts"},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status (pending, completed)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="priority",
     *         in="query",
     *         description="Filter by priority (low, medium, high)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name, email, or message",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of contacts",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Contact")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {


        $query = Contact::query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }


        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('message', 'like', "%$search%");
            });
        }

        return response()->json($query->get());

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/contacts",
     *     summary="Create a new contact",
     *     tags={"Contacts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Contact")
     *     ),
     *     @OA\Response(response=201, description="Contact created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request , NotificationService $notificationService)
    {
        try {


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
            'status' => 'nullable|string|in:pending,completed',
        ]);

        $contact = Contact::create($request->all());
        $notificationService->notifyAdmin('A new contact has been created: ' . $contact->name);

        event(new ContactCreated($contact));

        return response()->json($contact, 201);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/contacts/{id}",
     *     summary="Get a single contact",
     *     tags={"Contacts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Contact ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Contact details"),
     *     @OA\Response(response=404, description="Contact not found")
     * )
     */
    public function show($id)
    {
        try {
        return response()->json(Contact::findOrFail($id));

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/contacts/{id}",
     *     summary="Update a contact",
     *     tags={"Contacts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Contact ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Contact")
     *     ),
     *     @OA\Response(response=200, description="Contact updated"),
     *     @OA\Response(response=404, description="Contact not found")
     * )
     */
    public function update(Request $request, $id)
    {
        try {

        $contact = Contact::findOrFail($id);
        $contact->update($request->all());
        return response()->json($contact);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/contacts/{id}",
     *     summary="Delete a contact",
     *     tags={"Contacts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Contact ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Contact deleted"),
     *     @OA\Response(response=404, description="Contact not found")
     * )
     */
    public function destroy($id)
    {
        try {

        Contact::findOrFail($id)->delete();
        return response()->json(null, 204);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }
}
