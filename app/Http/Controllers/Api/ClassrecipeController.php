<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\ClassrecipeRepositoryInterface;

class ClassrecipeController extends CrudApiController
{
    public function __construct(ClassrecipeRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
