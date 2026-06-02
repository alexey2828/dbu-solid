<?php

namespace App\Services\Auth;

use App\Contracts\Services\Auth\AuthServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService implements AuthServiceInterface
{
    public function login(string $login, string $password): array
    {
        $user = User::where('login', $login)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new JWTException('Invalid credentials');
        }

        $token = JWTAuth::fromUser($user);

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ];
    }

    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'login' => $data['login'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'user',
        ]);

        return $user;
    }

    public function logout(): bool
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return true;
        } catch (JWTException $e) {
            return false;
        }
    }

    public function refresh(): string
    {
        try {
            return JWTAuth::refresh(JWTAuth::getToken());
        } catch (JWTException $e) {
            throw $e;
        }
    }

    public function me(): ?User
    {
        try {
            return JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return null;
        }
    }
}
