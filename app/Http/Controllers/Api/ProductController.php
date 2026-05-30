<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\ProductRepositoryInterface;

class ProductController extends BaseApiController
{
    public function __construct(ProductRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
