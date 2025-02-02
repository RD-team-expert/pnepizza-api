<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use Illuminate\Http\Request;


class MilestoneController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/milestones",
     *     summary="Get all milestones",
     *     tags={"Milestones"},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter milestones by status (pending, completed, in_progress)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Filter milestones from a start date",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Filter milestones until an end date",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by milestone title or description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of milestones",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Milestone"))
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

        $query = Milestone::query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('start_date')) {
            $query->where('date', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $query->where('date', '<=', $request->input('end_date'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
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
     *     path="/api/v1/milestones",
     *     summary="Create a new milestone",
     *     tags={"Milestones"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Milestone")
     *     ),
     *     @OA\Response(response=201, description="Milestone created successfully"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:pending,completed,in_progress',
        ]);

        $milestone = Milestone::create($request->all());
        return response()->json($milestone, 201);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/milestones/{id}",
     *     summary="Get a single milestone",
     *     tags={"Milestones"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Milestone ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Milestone details"),
     *     @OA\Response(response=404, description="Milestone not found"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function show($id)
    {
        try {
        return response()->json(Milestone::findOrFail($id));

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/milestones/{id}",
     *     summary="Update a milestone",
     *     tags={"Milestones"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Milestone ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Milestone")
     *     ),
     *     @OA\Response(response=200, description="Milestone updated successfully"),
     *     @OA\Response(response=404, description="Milestone not found"),
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

        $milestone = Milestone::findOrFail($id);
        $milestone->update($request->all());
        return response()->json($milestone);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/milestones/{id}",
     *     summary="Delete a milestone",
     *     tags={"Milestones"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Milestone ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Milestone deleted"),
     *     @OA\Response(response=404, description="Milestone not found"),
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

        Milestone::findOrFail($id)->delete();
        return response()->json(null, 204);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }
}
