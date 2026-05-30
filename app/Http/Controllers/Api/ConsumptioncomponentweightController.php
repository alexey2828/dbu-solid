<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\ConsumptioncomponentweightRepositoryInterface;

class ConsumptioncomponentweightController extends BaseApiController
{
    public function __construct(ConsumptioncomponentweightRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
