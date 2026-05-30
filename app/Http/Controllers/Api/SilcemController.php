<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\SilcemRepositoryInterface;

class SilcemController extends BaseApiController
{
    public function __construct(SilcemRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
