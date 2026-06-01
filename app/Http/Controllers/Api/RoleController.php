<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\RoleRepositoryInterface;

class RoleController extends CrudApiController
{
    public function __construct(RoleRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
