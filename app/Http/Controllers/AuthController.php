<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    const TOKEN_NAME = 'Masagena@@';

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", example="1|abcdefg123456")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     * @param Request $request
     * @return
     */
//    public function login(Request $request)
//    {
//        try {
//
//
//        $request->validate([
//            'email' => 'required|email',
//            'password' => 'required|string',
//        ]);
//
//        $user = User::where('email', $request->email)->first();
//
//        if (! $user || ! Hash::check($request->password, $user->password)) {
//            throw ValidationException::withMessages([
//                'email' => ['The provided credentials are incorrect.'],
//            ]);
//        }
//
//        $token = $user->createToken($user->name . '-' . self::TOKEN_NAME)->plainTextToken;
//
//        return (new UserResource($user))->additional([
//            'token' => $token,
//        ]);
//        } catch (\Exception $exception) {
//            return response()->json([
//                'msg' => $exception->getMessage(),
//            ]);
//        }
//    }


    public function login(Request $request)
    {

//        dd($request->session());
        try {



            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

               $user= Auth::user();
                return response()->json([
                     'data' => $user,
                    'message' => 'Authenticated',
                ]);
            }




            return response()->json(['message' => 'Invalid credentials'], 401);
    } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="User logout",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     )
     * )
     */
//    public function logout(Request $request)
//    {
//        try {
//            // Get the authenticated user
//            $user = Auth::user();
//
//            // Check if the user is authenticated
//            if ($user) {
//                // Delete all tokens associated with the user
//                $user->tokens()->delete();
//
//                // Return a success response
//                return response()->json(['message' => 'Successfully logged out'], 200);
//            } else {
//                // If the user is not authenticated, return an error response
//                return response()->json(['message' => 'User not authenticated'], 401);
//            }
//
//        } catch (\Exception $exception) {
//            return response()->json([
//                'msg' => $exception->getMessage(),
//            ]);
//        }
//    }

    public function logout(Request $request)
    {
        try {

        // Invalidate the session and regenerate the CSRF token.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Successfully logged out'], 200);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }





}
