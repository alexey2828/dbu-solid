<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\BsuRepositoryInterface;

class BsuController extends BaseApiController
{
    public function __construct(BsuRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
