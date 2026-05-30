<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\CustomerRepositoryInterface;

class CustomerController extends BaseApiController
{
    public function __construct(CustomerRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
