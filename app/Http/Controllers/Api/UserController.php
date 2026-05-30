<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\UserRepositoryInterface;

class UserController extends BaseApiController
{
    public function __construct(UserRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
