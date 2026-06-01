<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\CompmainRepositoryInterface;

class CompmainController extends CrudApiController
{
    public function __construct(CompmainRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
