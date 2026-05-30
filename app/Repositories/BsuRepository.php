<?php

namespace App\Repositories;

use App\Contracts\Repositories\BsuRepositoryInterface;
use App\Models\Bsu;

class BsuRepository extends BaseRepository implements BsuRepositoryInterface
{
    protected array $filterable = ['id', 'code', 'codePlant', 'name', 'vMixer', 'isWork'];

    public function __construct(Bsu $model)
    {
        parent::__construct($model);
    }
}
