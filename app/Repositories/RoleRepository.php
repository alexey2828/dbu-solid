<?php

namespace App\Repositories;

use App\Contracts\Repositories\RoleRepositoryInterface;
use App\Models\Role;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    protected array $filterable = ['id', 'code', 'name'];

    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}
