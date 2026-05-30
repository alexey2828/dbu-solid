<?php

namespace App\Repositories;

use App\Contracts\Repositories\MixtureRepositoryInterface;
use App\Models\Mixture;

class MixtureRepository extends BaseRepository implements MixtureRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Mixture $model)
    {
        parent::__construct($model);
    }
}
