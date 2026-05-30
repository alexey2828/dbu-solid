<?php

namespace App\Repositories;

use App\Contracts\Repositories\MainstateRepositoryInterface;
use App\Models\Mainstate;

class MainstateRepository extends BaseRepository implements MainstateRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Mainstate $model)
    {
        parent::__construct($model);
    }
}
