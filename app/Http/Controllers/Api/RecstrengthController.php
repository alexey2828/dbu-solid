<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\RecstrengthRepositoryInterface;

class RecstrengthController extends CrudApiController
{
    public function __construct(RecstrengthRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
