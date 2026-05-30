<?php

namespace App\Repositories;

use App\Contracts\Repositories\DispatcherRepositoryInterface;
use App\Models\Dispatcher;

class DispatcherRepository extends BaseRepository implements DispatcherRepositoryInterface
{
    public function __construct(Dispatcher $model)
    {
        parent::__construct($model);
    }
}
