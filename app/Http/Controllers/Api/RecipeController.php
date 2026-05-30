<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\RecipeRepositoryInterface;

class RecipeController extends BaseApiController
{
    public function __construct(RecipeRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
