<?php

namespace App\Repositories;

use App\Contracts\Repositories\ClassrecipeRepositoryInterface;
use App\Models\Classrecipe;

class ClassrecipeRepository extends BaseRepository implements ClassrecipeRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Classrecipe $model)
    {
        parent::__construct($model);
    }
}
