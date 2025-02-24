<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LocationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/locations",
     *     summary="Create a new location",
     *     tags={"Locations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Location")
     *     ),
     *     @OA\Response(response=201, description="Location created successfully"),
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

      $v =  $request->validate([
            'name' => 'required|string',
            'image_url' => 'nullable|url',
            'street' => 'required|string',
            'city' => 'required|string',
            'latitude ' => 'string',
            'longitude' => 'string',
            'state' => 'required|string',
            'zip' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'lc_url' => 'boolean',
        ]);

        $location = Location::create($request->all());
        return response()->json($location, 201);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/locations",
     *     summary="Get all locations",
     *     tags={"Locations"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name or street",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status (true or false)",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of locations",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Location"))
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




        $query = Location::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('street', 'like', '%' . $request->search . '%');
        }


            $query->where('status', 'active');



            return response()->json($query->get());

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/locations/{id}",
     *     summary="Get a single location",
     *     tags={"Locations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Location ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Location details"),
     *     @OA\Response(response=404, description="Location not found"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function show()
    {
        try {
            $user = Auth::user();
            $query = Location::query();

                return response()->json($query->get());


        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/locations/{id}",
     *     summary="Update a location",
     *     tags={"Locations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Location ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Location")
     *     ),
     *     @OA\Response(response=200, description="Location updated"),
     *     @OA\Response(response=404, description="Location not found"),
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

        $location = Location::findOrFail($id);
        $location->update($request->all());
        return response()->json($location);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/locations/{id}",
     *     summary="Delete a location",
     *     tags={"Locations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Location ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Location deleted"),
     *     @OA\Response(response=404, description="Location not found"),
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

        Location::findOrFail($id)->delete();
        return response()->json(null, 204);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }
}
