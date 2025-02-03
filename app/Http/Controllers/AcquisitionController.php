<?php

namespace App\Http\Controllers;

use App\Events\AcquisitionCreated;
use App\Http\Controllers\Controller;
use App\Models\Acquisition;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Exception;



class AcquisitionController extends Controller
{




    /**
     * @OA\Get(
     *     path="/api/v1/acquisitions",
     *     summary="Get all acquisitions",
     *     tags={"Acquisitions"},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="priority",
     *         in="query",
     *         description="Filter by priority",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name, email, city, or state",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of acquisitions",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Acquisition"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $query = Acquisition::query();

            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->has('priority')) {
                $query->where('priority', $request->input('priority'));
            }

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
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/acquisitions",
     *     summary="Create a new acquisition",
     *     tags={"Acquisitions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Acquisition")
     *     ),
     *     @OA\Response(response=201, description="Acquisition created"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request, NotificationService $notificationService)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'status' => 'nullable|string|in:New,In Review,Contacted,Closed',
            'priority' => 'nullable|string|in:High,Medium,Low',
        ]);

        try {
            if (Acquisition::where('email', $validatedData['email'])->exists()) {
                throw new Exception('A user with this email already exists.');
            }

            $acquisition = Acquisition::create($validatedData);

            // Notify the admin
            $notificationService->notifyAdmin('A new acquisition has been created: ' . $acquisition->name);

            // Dispatch the event (if needed)
            event(new AcquisitionCreated($acquisition));

            return response()->json($acquisition, 201);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/acquisitions/{id}",
     *     summary="Get a single acquisition",
     *     tags={"Acquisitions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Acquisition ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Acquisition details"),
     *     @OA\Response(response=404, description="Acquisition not found")
     * )
     */
    public function show($id)
    {
        try {
        $acquisition = Acquisition::findOrFail($id);
        return response()->json($acquisition);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/acquisitions/{id}",
     *     summary="Update an acquisition",
     *     tags={"Acquisitions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Acquisition ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Acquisition")
     *     ),
     *     @OA\Response(response=200, description="Acquisition updated"),
     *     @OA\Response(response=404, description="Acquisition not found")
     * )
     */
    public function update(Request $request, $id)
    {

        try {


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

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/acquisitions/{id}",
     *     summary="Delete an acquisition",
     *     tags={"Acquisitions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Acquisition ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Acquisition deleted"),
     *     @OA\Response(response=404, description="Acquisition not found")
     * )
     */
    public function destroy($id)
    {
        try {


        $acquisition = Acquisition::findOrFail($id);
        $acquisition->delete();
        return response()->json([1], 204);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }
}
