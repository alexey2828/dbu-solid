<?php

namespace App\Contracts\Services\Auth;

use App\Models\User;

interface AuthServiceInterface
{
    /**
     * Authenticate user and return tokens.
     *
     * @param string $login
     * @param string $password
     * @return array ['access_token', 'refresh_token', 'expires_in']
     */
    public function login(string $login, string $password): array;

    /**
     * Register new user.
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User;

    /**
     * Logout current user (invalidate token).
     *
     * @return bool
     */
    public function logout(): bool;

    /**
     * Refresh access token using current token.
     *
     * @return string New access token
     */
    public function refresh(): string;

    /**
     * Get currently authenticated user.
     *
     * @return User|null
     */
    public function me(): ?User;
}
