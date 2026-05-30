<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ResourceRequest;
use Illuminate\Http\JsonResponse;

class ResourceController extends BaseApiController
{
    public function __construct()
    {
        //
    }

    public function index(): JsonResponse
    {
        return parent::index();
    }

    public function show(int|string $id): JsonResponse
    {
        return parent::show($this->resolveRouteId($id));
    }

    public function store(ResourceRequest $request): JsonResponse
    {
        return parent::store($request);
    }

    public function update(ResourceRequest $request, int|string $id): JsonResponse
    {
        return parent::update($request, $this->resolveRouteId($id));
    }

    public function destroy(int|string $id): JsonResponse
    {
        return parent::destroy($this->resolveRouteId($id));
    }

    private function resolveRouteId(int|string $id): int
    {
        if (is_int($id)) {
            return $id;
        }

        if (! ctype_digit($id)) {
            abort(404, 'Record not found');
        }

        return (int) $id;
    }
}
