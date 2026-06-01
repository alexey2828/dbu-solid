<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ResourceRequest;
use App\Http\Resources\ApiResource;
use Illuminate\Http\JsonResponse;

abstract class CrudApiController extends BaseApiController
{
    public function update(ResourceRequest $request, int $id): JsonResponse
    {
        $record = $this->repository->update($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully',
            'data' => new ApiResource($record),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->repository->delete($id);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully',
        ]);
    }
}
