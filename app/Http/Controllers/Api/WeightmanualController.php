<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\WeightmanualRepositoryInterface;

class WeightmanualController extends CrudApiController
{
    public function __construct(WeightmanualRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
