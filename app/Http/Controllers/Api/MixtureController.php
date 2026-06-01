<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\MixtureRepositoryInterface;

class MixtureController extends CrudApiController
{
    public function __construct(MixtureRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
