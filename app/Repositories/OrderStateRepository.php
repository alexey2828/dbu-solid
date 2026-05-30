<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderStateRepositoryInterface;
use App\Models\OrderState;

class OrderStateRepository extends BaseRepository implements OrderStateRepositoryInterface
{
    public function __construct(OrderState $model)
    {
        parent::__construct($model);
    }
}
