<?php

namespace App\Contracts\Services\Ttn;

use App\Models\Ttn;

interface TtnUpdateServiceInterface
{
    public function updateAndPublish(Ttn $ttn, array $data): Ttn;
}
