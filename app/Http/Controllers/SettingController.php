<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/settings",
     *     summary="Get the first Setting record",
     *     tags={"Settings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved the first Setting record",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="key", type="string", example="value"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No settings found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No settings found.")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $setting = Setting::first();

        if ($setting) {
            return response()->json([
                'success' => true,
                'data' => $setting
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No settings found.'
            ], 404);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/settings/{id}",
     *     summary="Get a specific Setting record by ID",
     *     tags={"Settings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Setting",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved the Setting record",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="key", type="string", example="value"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Setting not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Setting not found.")
     *         )
     *     )
     * )
     */
    public function view($id)
    {
        $setting = Setting::find($id);

        if ($setting) {
            return response()->json([
                'success' => true,
                'data' => $setting
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found.'
            ], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/settings/{id}",
     *     summary="Update a Setting record",
     *     tags={"Settings"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Setting to be updated",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="website_title", type="string", example="Updated Title"),
     *             @OA\Property(property="keywords", type="string", example="keyword1, keyword2"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="og_title", type="string", example="Updated OG Title"),
     *             @OA\Property(property="og_image_url", type="string", example="https://example.com/image.jpg"),
     *             @OA\Property(property="og_description", type="string", example="Updated OG description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Setting updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="website_title", type="string", example="Updated Title"),
     *                 @OA\Property(property="keywords", type="string", example="keyword1, keyword2"),
     *                 @OA\Property(property="description", type="string", example="Updated description"),
     *                 @OA\Property(property="og_title", type="string", example="Updated OG Title"),
     *                 @OA\Property(property="og_image_url", type="string", example="https://example.com/image.jpg"),
     *                 @OA\Property(property="og_description", type="string", example="Updated OG description")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Setting not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Setting not found.")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validated = $request->validate([
            'website_title' => 'required|string|max:255',
            'keywords' => 'required|string',
            'description' => 'required|string',
            'og_title' => 'required|string|max:255',
            'og_image_url' => 'required|url',
            'og_description' => 'required|string',
        ]);

        // Find the setting by ID
        $setting = Setting::find($id);

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found.'
            ], 404);
        }

        // Update the setting
        $setting->update($validated);

        return response()->json([
            'success' => true,
            'data' => $setting
        ]);
    }




}
