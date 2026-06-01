<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\SilcemRepositoryInterface;

class SilcemController extends CrudApiController
{
    public function __construct(SilcemRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
