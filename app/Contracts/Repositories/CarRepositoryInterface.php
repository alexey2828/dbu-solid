<?php

namespace App\Contracts\Repositories;

use App\Models\Car;

interface CarRepositoryInterface extends RepositoryInterface
{
    public function getByRFID(string $codeRFID): ?Car;
}
