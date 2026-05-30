<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\OrderRepositoryInterface;

class OrderController extends BaseApiController
{
    public function __construct(OrderRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
