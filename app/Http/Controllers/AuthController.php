<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email_or_phone", "password"},
     *             @OA\Property(property="email_or_phone", type="string", description="User email or phone number", example="john@example.com"),
     *             @OA\Property(property="password", type="string", description="User password", example="secret")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="status", type="string", example="success"),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     properties={
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="email", type="string"),
     *                         @OA\Property(property="phone", type="string"),
     *                         @OA\Property(property="email_verified_at", type="string", format="date-time"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     }
     *                 ),
     *                 @OA\Property(
     *                     property="authorization",
     *                     type="object",
     *                     properties={
     *                         @OA\Property(property="token", type="string"),
     *                         @OA\Property(property="type", type="string"),
     *                     }
     *                 ),
     *             }
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials"),
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'phone', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = JWTAuth::setToken($token)->toUser();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe", description="User's full name"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com", description="User's email address"),
     *             @OA\Property(property="phone", type="string", example="123456789", description="User's phone number"),
     *             @OA\Property(property="password", type="string", format="password", example="secret", description="User's password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret", description="Password confirmation"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *              @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 description="User details",
     *                 @OA\Property(property="id", type="integer", description="User ID"),
     *                 @OA\Property(property="name", type="string", description="User's name"),
     *                 @OA\Property(property="email", type="string", description="User's email address"),
     *                 @OA\Property(property="phone", type="string", description="User's phone number"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", description="User creation timestamp"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", description="User update timestamp"),
     *             ),
     *             @OA\Property(
     *                     property="authorization",
     *                     type="object",
     *                     properties={
     *                         @OA\Property(property="token", type="string"),
     *                         @OA\Property(property="type", type="string"),
     *                     }
     *                 ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Unable to generate token",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unable to generate token")
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to generate token',
            ], 500);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout the authenticated user",
     *     tags={"Authentication"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }
}
