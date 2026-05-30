<?php

namespace App\Repositories;

use App\Contracts\Repositories\ReccommentRepositoryInterface;
use App\Models\Reccomment;

class ReccommentRepository extends BaseRepository implements ReccommentRepositoryInterface
{
    public function __construct(Reccomment $model)
    {
        parent::__construct($model);
    }
}
