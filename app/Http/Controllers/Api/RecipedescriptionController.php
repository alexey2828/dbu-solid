<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\RecipedescriptionRepositoryInterface;

class RecipedescriptionController extends BaseApiController
{
    public function __construct(RecipedescriptionRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
