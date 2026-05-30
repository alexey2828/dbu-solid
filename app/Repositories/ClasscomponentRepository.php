<?php

namespace App\Repositories;

use App\Contracts\Repositories\ClasscomponentRepositoryInterface;
use App\Models\Classcomponent;

class ClasscomponentRepository extends BaseRepository implements ClasscomponentRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Classcomponent $model)
    {
        parent::__construct($model);
    }
}
