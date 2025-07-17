<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * The function handles user login authentication using JWT tokens in PHP.
     *
     * @param LoginRequest request The `login` function you provided seems to handle user
     * authentication using JWT (JSON Web Tokens). It validates the login request, generates a token if
     * the login is successful, and returns the token in the response.
     *
     * @return JsonResponse The `login` function returns a JSON response with a success message and
     * data containing an access token and token type if the login attempt is successful. If there is
     * an unauthorized error during the login attempt, it returns a JSON response with an error
     * message. In case of any other exception, it returns a JSON response with the error message from
     * the exception.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {

            if (! $token = JWTAuth::attempt($request->validated())) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $user = auth()->user();
            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            return response()->json([
                'message' => 'success',
                'data' => [
                    'access_token' => $token,
                    'token_type'   => 'bearer'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * The function `getUserAuthenticated` checks if a user is authenticated using JWT and returns the
     * user data or an error message.
     *
     * @return JsonResponse A JsonResponse is being returned. If the user is found and authenticated, a
     * JSON response with the user data is returned with a status code of 200. If there is an issue
     * with the token or authentication, an error message is returned with a status code of 400 or 404.
     */
    public function getUserAuthenticated(): JsonResponse
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }

            return response()->json([
                'data' => $user
            ], 200);

        } catch (JWTException $e) {

            return response()->json(['error' => 'Invalid token'], 400);
        }
    }
}
