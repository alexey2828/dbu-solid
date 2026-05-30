<?php

namespace App\Repositories;

use App\Contracts\Repositories\RecmarkaRepositoryInterface;
use App\Models\Recmarka;

class RecmarkaRepository extends BaseRepository implements RecmarkaRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Recmarka $model)
    {
        parent::__construct($model);
    }
}
