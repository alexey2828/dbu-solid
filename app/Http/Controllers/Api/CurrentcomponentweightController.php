<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\CurrentcomponentweightRepositoryInterface;

class CurrentcomponentweightController extends BaseApiController
{
    public function __construct(CurrentcomponentweightRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
