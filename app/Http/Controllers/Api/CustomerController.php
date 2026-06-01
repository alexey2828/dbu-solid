<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\CustomerRepositoryInterface;

class CustomerController extends CrudApiController
{
    public function __construct(CustomerRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
