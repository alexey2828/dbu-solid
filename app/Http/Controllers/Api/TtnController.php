<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\TtnRepositoryInterface;
use App\Contracts\Services\TtnUpdateServiceInterface;
use App\Http\Requests\TtnUpdateRequest;
use App\Models\Ttn;
use Illuminate\Http\JsonResponse;

class TtnController extends CrudApiController
{
    public function __construct(
        TtnRepositoryInterface $repository,
        private TtnUpdateServiceInterface $updateService
    ) {
        parent::__construct($repository);
    }

    public function publishUpdate(TtnUpdateRequest $request, Ttn $ttn): JsonResponse
    {
        try {
            $record = $this->updateService->updateAndPublish($ttn, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Record updated and MQTT message published successfully',
                'data' => $record,
            ]);
        } catch (\InvalidArgumentException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        } catch (\RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
