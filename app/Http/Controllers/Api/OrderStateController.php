<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\OrderStateRepositoryInterface;

class OrderStateController extends BaseApiController
{
    public function __construct(OrderStateRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
