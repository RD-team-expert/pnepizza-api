<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;



class ManageUserController extends Controller
{
    protected string $pathImage = 'public/user';

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get paginated list of users",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of users",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function index(): JsonResource
    {
        try {

        $users = User::paginate(10);
        return UserResource::collection($users);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=201, description="User created successfully"),
     *     @OA\Response(response=400, description="Invalid role selected"),
     *     @OA\Response(response=500, description="User creation failed"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {

        $validated = $request->validated();

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store($this->pathImage);
        }

        try {
            $user = User::create(array_merge($validated, ['image' => $imagePath]));
            $role = $validated['role'];

            if (!in_array($role, ['Admin', 'Acquisition', 'BRM'])) {
                return response()->json(['message' => 'Invalid role selected'], 400);
            }

            $user->assignRole($role);
        } catch (\Exception $e) {
            if ($imagePath) {
                Storage::delete($imagePath);
            }
            return response()->json(['message' => 'User creation failed', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get a single user",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User details"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function show(User $user): JsonResource
    {
        try {
        return new UserResource($user->loadMissing('roles'));

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update a user",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=200, description="User updated successfully"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=500, description="User update failed"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {

        $validated = $request->validated();
        $imagePath = $user->image;

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::delete($user->image);
            }
            $imagePath = $request->file('image')->store($this->pathImage);
        }

        try {
            $user->update(array_merge($validated, ['image' => $imagePath]));
            $user->syncRoles($validated['role']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User update failed', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="User deleted"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=500, description="User deletion failed"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            if ($user->image) {
                Storage::delete($user->image);
            }
            $user->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => 'User deletion failed'], 500);
        }

        return response()->json(null, 204);
    }
}
