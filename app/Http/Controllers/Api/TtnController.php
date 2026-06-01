<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\TtnRepositoryInterface;

class TtnController extends CrudApiController
{
    public function __construct(TtnRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
