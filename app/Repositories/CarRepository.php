<?php

namespace App\Repositories;

use App\Contracts\Repositories\CarRepositoryInterface;
use App\Models\Car;

class CarRepository extends BaseRepository implements CarRepositoryInterface
{
    public function __construct(Car $model)
    {
        parent::__construct($model);
    }

    public function getByRFID(string $rfid): ?Car
    {
        return $this->model->where('codeRFID', $rfid)->first();
    }
}
