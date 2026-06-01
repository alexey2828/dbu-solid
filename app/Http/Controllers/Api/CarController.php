<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\CarRepositoryInterface;
use App\Http\Resources\ApiResource;
use Illuminate\Http\JsonResponse;

class CarController extends CrudApiController
{
    public function __construct(private readonly CarRepositoryInterface $cars)
    {
        parent::__construct($cars);
    }

    public function show(int|string $id): JsonResponse
    {
        $car = ctype_digit((string) $id)
            ? $this->cars->find((int) $id)
            : $this->cars->getByRFID((string) $id);

        if (! $car) {
            abort(404, 'Record not found');
        }

        return response()->json([
            'success' => true,
            'data' => new ApiResource($car),
        ]);
    }
}
