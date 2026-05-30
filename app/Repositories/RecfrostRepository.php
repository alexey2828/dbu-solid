<?php

namespace App\Repositories;

use App\Contracts\Repositories\RecfrostRepositoryInterface;
use App\Models\Recfrost;

class RecfrostRepository extends BaseRepository implements RecfrostRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Recfrost $model)
    {
        parent::__construct($model);
    }
}
