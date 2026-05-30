<?php

namespace App\Repositories;

use App\Contracts\Repositories\RecwatRepositoryInterface;
use App\Models\Recwat;

class RecwatRepository extends BaseRepository implements RecwatRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Recwat $model)
    {
        parent::__construct($model);
    }
}
