<?php

namespace App\Repositories;

use App\Contracts\Repositories\SilcemRepositoryInterface;
use App\Models\Silcem;

class SilcemRepository extends BaseRepository implements SilcemRepositoryInterface
{
    protected array $filterable = ['id', 'code', 'codeBSU'];

    public function __construct(Silcem $model)
    {
        parent::__construct($model);
    }
}
