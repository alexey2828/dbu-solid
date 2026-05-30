<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\TtnRepositoryInterface;

class TtnController extends BaseApiController
{
    public function __construct(TtnRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
