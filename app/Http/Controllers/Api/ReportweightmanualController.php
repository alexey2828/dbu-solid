<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\ReportweightmanualRepositoryInterface;

class ReportweightmanualController extends BaseApiController
{
    public function __construct(ReportweightmanualRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
