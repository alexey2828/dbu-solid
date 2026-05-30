<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected array $filterable = ['id', 'idPlant', 'idCustomer', 'dispatcher', 'nameRecipe', 'dateStart', 'dateFinish'];

    public function __construct(Order $model)
    {
        parent::__construct($model);
    }
}
