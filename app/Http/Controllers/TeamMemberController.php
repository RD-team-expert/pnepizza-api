<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;


class TeamMemberController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/team-members",
     *     summary="Get all team members",
     *     tags={"Team Members"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="Filter team members by role",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status (active, inactive)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name or bio",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of team members",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/TeamMember"))
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

        $query = TeamMember::query();

        if ($request->has('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('bio', 'like', "%$search%");
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
     *     path="/api/team-members",
     *     summary="Create a new team member",
     *     tags={"Team Members"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TeamMember")
     *     ),
     *     @OA\Response(response=201, description="Team member created successfully"),
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
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'profile_image' => 'nullable|string',
            'bio' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $teamMember = TeamMember::create($request->all());
        return response()->json($teamMember, 201);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/team-members/{id}",
     *     summary="Get a single team member",
     *     tags={"Team Members"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Team Member ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Team member details"),
     *     @OA\Response(response=404, description="Team member not found"),
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
        return response()->json(TeamMember::findOrFail($id));
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/team-members/{id}",
     *     summary="Update a team member",
     *     tags={"Team Members"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Team Member ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TeamMember")
     *     ),
     *     @OA\Response(response=200, description="Team member updated successfully"),
     *     @OA\Response(response=404, description="Team member not found"),
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

        $teamMember = TeamMember::findOrFail($id);
        $teamMember->update($request->all());
        return response()->json($teamMember);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/team-members/{id}",
     *     summary="Delete a team member",
     *     tags={"Team Members"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Team Member ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Team member deleted"),
     *     @OA\Response(response=404, description="Team member not found"),
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

        TeamMember::findOrFail($id)->delete();
        return response()->json(null, 204);
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }
}
