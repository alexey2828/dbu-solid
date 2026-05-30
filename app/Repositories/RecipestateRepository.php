<?php

namespace App\Repositories;

use App\Contracts\Repositories\RecipestateRepositoryInterface;
use App\Models\Recipestate;

class RecipestateRepository extends BaseRepository implements RecipestateRepositoryInterface
{
    public function __construct(Recipestate $model)
    {
        parent::__construct($model);
    }
}
