<?php

namespace App\Repositories;

use App\Contracts\Repositories\ReportweightmanualRepositoryInterface;
use App\Models\Reportweightmanual;

class ReportweightmanualRepository extends BaseRepository implements ReportweightmanualRepositoryInterface
{
    protected array $filterable = ['id', 'code'];

    public function __construct(Reportweightmanual $model)
    {
        parent::__construct($model);
    }
}
