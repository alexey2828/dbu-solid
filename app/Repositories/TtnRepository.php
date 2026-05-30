<?php

namespace App\Repositories;

use App\Contracts\Repositories\TtnRepositoryInterface;
use App\Models\Ttn;

class TtnRepository extends BaseRepository implements TtnRepositoryInterface
{
    protected array $filterable = ['id', 'idPlant', 'idOrder', 'dispatcher', 'vProduct', 'driver', 'car', 'finishAdress', 'finishDate', 'state', 'isPause', 'idProduct', 'idBsu'];

    public function __construct(Ttn $model)
    {
        parent::__construct($model);
    }
}
