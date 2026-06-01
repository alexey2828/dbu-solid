<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\RecipeRepositoryInterface;

class RecipeController extends CrudApiController
{
    public function __construct(RecipeRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
