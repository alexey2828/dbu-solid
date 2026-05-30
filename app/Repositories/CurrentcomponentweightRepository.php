<?php

namespace App\Repositories;

use App\Contracts\Repositories\CurrentcomponentweightRepositoryInterface;
use App\Models\Currentcomponentweight;

class CurrentcomponentweightRepository extends BaseRepository implements CurrentcomponentweightRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Currentcomponentweight $model)
    {
        parent::__construct($model);
    }
}
