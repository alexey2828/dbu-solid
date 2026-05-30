<?php

namespace App\Repositories;

use App\Contracts\Repositories\ReportcurrentloopRepositoryInterface;
use App\Models\Reportcurrentloop;

class ReportcurrentloopRepository extends BaseRepository implements ReportcurrentloopRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Reportcurrentloop $model)
    {
        parent::__construct($model);
    }
}
