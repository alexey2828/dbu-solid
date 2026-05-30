<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\RecmarkaRepositoryInterface;

class RecmarkaController extends BaseApiController
{
    public function __construct(RecmarkaRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
