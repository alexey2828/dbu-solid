<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\RecwatRepositoryInterface;

class RecwatController extends BaseApiController
{
    public function __construct(RecwatRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
