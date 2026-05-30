<?php

namespace App\Repositories;

use App\Contracts\Repositories\RecstrengthRepositoryInterface;
use App\Models\Recstrength;

class RecstrengthRepository extends BaseRepository implements RecstrengthRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Recstrength $model)
    {
        parent::__construct($model);
    }
}
