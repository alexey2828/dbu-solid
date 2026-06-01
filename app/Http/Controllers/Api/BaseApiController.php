<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\RepositoryInterface;
use App\Http\Requests\Api\ResourceRequest;
use App\Http\Resources\ApiResource;
use Illuminate\Http\JsonResponse;

abstract class BaseApiController
{
    public RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(): JsonResponse
    {
        $filters = array_filter(
            request()->query(),
            static fn ($value) => $value !== null && $value !== ''
        );

        $data = ! empty($filters) && method_exists($this->repository, 'search')
            ? $this->repository->search($filters)
            : $this->repository->all();

        return response()->json([
            'success' => true,
            'data' => ApiResource::collection($data),
            'total' => $data->count(),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $record = $this->repository->find($id);

        return response()->json([
            'success' => true,
            'data' => new ApiResource($record),
        ]);
    }

    public function store(ResourceRequest $request): JsonResponse
    {
        $record = $this->repository->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Record created successfully',
            'data' => new ApiResource($record),
        ], 201);
    }
}
