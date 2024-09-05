<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\UserLoggedIn;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    /**
    * Get a JWT via given credentials.
    * @return \Illuminate\Http\JsonResponse
    */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth('api')->user();

        Notification::route('mail', 'dellanosites@gmail.com')
            ->notify(new UserLoggedIn($user));

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Get the token array structure.
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
