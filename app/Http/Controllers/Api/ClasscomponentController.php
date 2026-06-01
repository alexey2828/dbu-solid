<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\ClasscomponentRepositoryInterface;

class ClasscomponentController extends CrudApiController
{
    public function __construct(ClasscomponentRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
