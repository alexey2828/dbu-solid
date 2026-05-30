<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\CompRepositoryInterface;

class CompController extends BaseApiController
{
    public function __construct(CompRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
