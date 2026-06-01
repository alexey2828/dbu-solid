<?php

use App\Http\Middleware\JwtAuthenticate;
use Illuminate\Http\Request;

it('rejects requests without a jwt token', function () {
    $middleware = new JwtAuthenticate();

    $response = $middleware->handle(
        Request::create('/api/recipe', 'GET'),
        fn () => response()->json(['ok' => true])
    );

    expect($response->status())->toBe(401);
    expect($response->getData(true)['error'])->toBe('Token not provided');
});
