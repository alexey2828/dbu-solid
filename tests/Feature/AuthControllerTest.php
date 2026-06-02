<?php

use App\Contracts\Services\Auth\AuthServiceInterface;
use App\Http\Controllers\Api\AuthController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    $this->authService = Mockery::mock(AuthServiceInterface::class);
});

afterEach(function () {
    Mockery::close();
});

test('auth login uses login field and returns token response', function () {
    $request = LoginRequest::create('/api/auth/login', 'POST', [
        'login' => 'qq2820022',
        'password' => '123456',
    ]);

    $request->setValidator(Validator::make($request->all(), $request->rules()));

    $this->authService
        ->shouldReceive('login')
        ->once()
        ->with('qq2820022', '123456')
        ->andReturn([
            'access_token' => 'fake-token',
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ]);

    $controller = new AuthController($this->authService);
    $response = $controller->login($request);

    expect($response)->toBeInstanceOf(Illuminate\Http\JsonResponse::class);
    $data = $response->getData(true);
    expect($data['success'])->toBeTrue();
    expect($data['data']['access_token'])->toBe('fake-token');
});
