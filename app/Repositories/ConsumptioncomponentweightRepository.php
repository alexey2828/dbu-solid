<?php

namespace App\Repositories;

use App\Contracts\Repositories\ConsumptioncomponentweightRepositoryInterface;
use App\Models\Consumptioncomponentweight;

class ConsumptioncomponentweightRepository extends BaseRepository implements ConsumptioncomponentweightRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Consumptioncomponentweight $model)
    {
        parent::__construct($model);
    }
}
