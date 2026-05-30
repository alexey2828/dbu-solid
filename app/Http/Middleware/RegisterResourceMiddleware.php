<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisterResourceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $resource = $request->route('resource');

        if (! $resource) {
            return $next($request);
        }

        $resource = Str::of($resource)->lower()->value();
        $config = config("models.{$resource}");

        if (! $config) {
            $singularResource = Str::singular($resource);
            $config = config("models.{$singularResource}");

            if ($config) {
                $request->route()->setParameter('resource', $singularResource);
            }
        }

        if (! $config) {
            abort(404, "Resource '{$resource}' not found");
        }

        $repositoryClass = $config['repository'];
        $controller = $request->route()->getController();

        if ($controller) {
            $controller->repository = app($repositoryClass);
        }

        return $next($request);
    }
}
