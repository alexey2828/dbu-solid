<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\TtnstateRepositoryInterface;

class TtnstateController extends BaseApiController
{
    public function __construct(TtnstateRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
