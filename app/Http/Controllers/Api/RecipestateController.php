<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\RecipestateRepositoryInterface;

class RecipestateController extends BaseApiController
{
    public function __construct(RecipestateRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
