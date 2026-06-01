<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\BsuRepositoryInterface;

class BsuController extends CrudApiController
{
    public function __construct(BsuRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
