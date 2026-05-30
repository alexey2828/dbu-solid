<?php

namespace App\Repositories;

use App\Contracts\Repositories\CompRepositoryInterface;
use App\Models\Comp;

class CompRepository extends BaseRepository implements CompRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Comp $model)
    {
        parent::__construct($model);
    }
}
