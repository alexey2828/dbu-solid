<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\RecmobilityRepositoryInterface;

class RecmobilityController extends BaseApiController
{
    public function __construct(RecmobilityRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
