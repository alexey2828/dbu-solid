<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\ReccommentRepositoryInterface;

class ReccommentController extends BaseApiController
{
    public function __construct(ReccommentRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
