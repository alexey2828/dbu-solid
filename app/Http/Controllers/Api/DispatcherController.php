<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\DispatcherRepositoryInterface;

class DispatcherController extends BaseApiController
{
    public function __construct(DispatcherRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
