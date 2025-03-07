<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;



class ManageUserController extends Controller
{
    protected string $pathImage = 'public/user';

    /**
     * @OA\Get(
     *     path="/api/v1/users",
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
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);

              $users = User::paginate($perPage);
                $data=[];
                foreach ($users as $user){
                    $data[] =  new UserResource($user->loadMissing('roles'));
                }

            return response()->json([
                'current_page' => $users->currentPage(),
                'data' => $data, // The actual media items
                'first_page_url' => $users->url(1), // URL to the first page
                'from' => $users->firstItem(), // Starting item number
                'last_page' => $users->lastPage(), // Last page number
                'last_page_url' => $users->url($users->lastPage()), // URL to the last page
                'next_page_url' => $users->nextPageUrl(), // URL to the next page
                'path' => $users->path(), // Base path for pagination URLs
                'per_page' => $users->perPage(), // Number of items per page
                'prev_page_url' => $users->previousPageUrl(), // URL to the previous page
                'to' => $users->lastItem(), // Ending item number
                'total' => $users->total(), // Total number of items
            ]);


        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users",
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
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $imagePath = $file->storeAs('user', $fileName, 'public');


        }

        try {

            $user = User::create(array_merge($validated, ['image' => $imagePath]));
//            $user->image = Storage::url($imagePath);
            $role = $validated['role'];

            if (!in_array($role, ['Admin', 'Acquisition', 'BRM'])) {
                return response()->json(['message' => 'Invalid role selected'], 400);
            }

            $user->assignRole($role);

           $user = new UserResource($user->loadMissing('roles'));
        } catch (\Exception $e) {
            if ($imagePath) {
                Storage::delete($imagePath);
            }
            return response()->json(['message' => 'User creation failed', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'User created successfully', 'user' =>$user], 201);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/{id}",
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
    public function show(User $user)
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
     *     path="/api/v1/users/{id}",
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
        try {
            $validated = $request->validated();

            // Hash password if provided
            if (isset($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            }

            // Handle image update
            $imagePath = $user->image;
            if ($request->hasFile('image')) {
                // Delete old image if exists

                Storage::disk('public')->delete([
                    str_replace('/storage/', '', $imagePath)
                ]);


                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $imagePath = $file->storeAs('user', $fileName, 'public');

                $user->image = Storage::url('public/'.$imagePath);


                $user->save();
            }

            // Update user with validated data
            if (isset($validated['name'])){
                $user->update(['name' => $validated['name']]);
            }
            if (isset($validated['email'])){
                $user->update(['email' => $validated['email']]);
            }
            if (isset($validated['image'])){
                $user->update(['image' => $imagePath]);
            }

            // Update role if provided
            if ($request->has('role')) {
                $user->syncRoles($validated['role']);
            }
            $user = new UserResource($user->loadMissing('roles'));


            return response()->json([
                'message' => 'User updated successfully',
                'user' => ($user)
            ]);

        } catch (\Exception $exception) {
            // Rollback image changes if error occurred
            if (isset($imagePath) && $imagePath !== $user->image) {
                Storage::delete($imagePath);
            }
        return response()->json([
            'message' => 'User update failed',
            'error' => $exception->getMessage()
        ], 500);
    }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/users/{id}",
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
