<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\DispatcherRepositoryInterface;

class DispatcherController extends CrudApiController
{
    public function __construct(DispatcherRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
