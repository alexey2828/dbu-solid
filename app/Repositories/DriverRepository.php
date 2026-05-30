<?php

namespace App\Repositories;

use App\Contracts\Repositories\DriverRepositoryInterface;
use App\Models\Driver;

class DriverRepository extends BaseRepository implements DriverRepositoryInterface
{
    public function __construct(Driver $model)
    {
        parent::__construct($model);
    }
}
