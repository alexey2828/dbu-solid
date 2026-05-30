<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\RecstrengthRepositoryInterface;

class RecstrengthController extends BaseApiController
{
    public function __construct(RecstrengthRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
