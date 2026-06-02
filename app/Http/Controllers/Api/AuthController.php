<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\Auth\AuthServiceInterface;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController
{
    public function __construct(private AuthServiceInterface $authService) {}

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();
            $result = $this->authService->login($credentials['login'], $credentials['password']);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => $result,
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
            ], 500);
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = $this->authService->register($data);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function refresh(): JsonResponse
    {
        try {
            $token = $this->authService->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Token refreshed',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => config('jwt.ttl') * 60,
                ],
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token refresh failed',
            ], 401);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
            ], 500);
        }
    }

    public function me(): JsonResponse
    {
        $user = $this->authService->me();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'login' => $user->login,
                'phone' => $user->phone,
            ],
        ]);
    }
}
