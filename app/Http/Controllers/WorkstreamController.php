<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Token;


class WorkstreamController extends Controller
{
    /**
     * Refresh the Workstream token.
     *
     * @return string|null The new token or null if the refresh fails.
     */
    private function refreshToken()
    {
        Log::info('Attempting to refresh token...');

        $response = Http::asForm()->post(env('WORKSTREAM_REFRESH_URL'), [
            'grant_type' => 'client_credentials',
            'client_id' => env('WORKSTREAM_CLIENT_ID'),
            'client_secret' => env('WORKSTREAM_CLIENT_SECRET'),
            'token' => env('WORKSTREAM_TOKEN'),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $newToken = $data['token'];

            Log::info('Token refreshed successfully.', ['token' => $newToken]);

            Token::updateOrCreate(
                ['name' => 'workstream'],
                [
                    'token' => $newToken,
                    'expires_at' => now()->addSeconds($data['expires_in']),
                ]
            );

            Log::info('Token saved to database successfully.');
            return $newToken;
        }

        Log::error('Failed to refresh token.', ['response' => $response->json()]);
        return null;
    }

    /**
     * Get the current Workstream token from the database.
     *
     * @return string|null
     */
    private function getToken()
    {
        $token = Token::where('name', 'workstream')->first();

        if ($token && $token->expires_at > now()) {
            Log::info('Using existing token from the database.');
            return $token->token;
        }

        Log::info('Token expired or not found. Refreshing token...');
        return $this->refreshToken();
    }

    /**
     * @OA\Get(
     *     path="/api/positions",
     *     summary="Get all published Workstream positions",
     *     tags={"Workstream"},
     *     @OA\Response(
     *         response=200,
     *         description="List of published positions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=123),
     *                 @OA\Property(property="title", type="string", example="Software Engineer"),
     *                 @OA\Property(property="location", type="string", example="New York"),
     *                 @OA\Property(property="status", type="string", example="published"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Failed to fetch positions."),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function getPositions()
    {
        $token = $this->getToken();

        if (!$token) {
            return response()->json(['error' => 'Failed to fetch token.'], 500);
        }

        $response = Http::withToken($token)
            ->get(env('WORKSTREAM_POSITIONS_URL'), [
                'status' => 'published',
            ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'data' => $response->json(),
            ], 200);
        }

        Log::error('Failed to fetch positions.', ['response' => $response->json()]);
        return response()->json(['error' => 'Failed to fetch positions.'], 500);
    }
}
