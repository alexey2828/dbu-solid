<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\ReccommentRepositoryInterface;

class ReccommentController extends CrudApiController
{
    public function __construct(ReccommentRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
