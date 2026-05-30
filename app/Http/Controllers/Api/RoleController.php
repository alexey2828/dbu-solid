<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\RoleRepositoryInterface;

class RoleController extends BaseApiController
{
    public function __construct(RoleRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
