<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\MixtureRepositoryInterface;

class MixtureController extends BaseApiController
{
    public function __construct(MixtureRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
