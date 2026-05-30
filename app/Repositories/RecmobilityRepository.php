<?php

namespace App\Repositories;

use App\Contracts\Repositories\RecmobilityRepositoryInterface;
use App\Models\Recmobility;

class RecmobilityRepository extends BaseRepository implements RecmobilityRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Recmobility $model)
    {
        parent::__construct($model);
    }
}
