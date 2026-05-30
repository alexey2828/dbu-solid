<?php

namespace App\Repositories;

use App\Contracts\Repositories\RecipeRepositoryInterface;
use App\Models\Recipe;

class RecipeRepository extends BaseRepository implements RecipeRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Recipe $model)
    {
        parent::__construct($model);
    }
}
