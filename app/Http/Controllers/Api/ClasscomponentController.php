<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\ClasscomponentRepositoryInterface;

class ClasscomponentController extends BaseApiController
{
    public function __construct(ClasscomponentRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
