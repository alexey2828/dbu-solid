<?php

namespace App\Repositories;

use App\Contracts\Repositories\PlantRepositoryInterface;
use App\Models\Plant;

class PlantRepository extends BaseRepository implements PlantRepositoryInterface
{
    protected array $filterable = ['id', 'codePlant'];

    public function __construct(Plant $model)
    {
        parent::__construct($model);
    }
}
