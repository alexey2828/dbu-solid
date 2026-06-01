<?php

namespace App\Repositories;

use App\Contracts\Repositories\RecipedescriptionRepositoryInterface;
use App\Models\Recipedescription;

class RecipedescriptionRepository extends BaseRepository implements RecipedescriptionRepositoryInterface
{
    protected array $filterable = ['id', 'codeRecipe'];

    public function __construct(Recipedescription $model)
    {
        parent::__construct($model);
    }
}
