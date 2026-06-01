<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\DriverRepositoryInterface;

class DriverController extends CrudApiController
{
    public function __construct(DriverRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
