<?php

namespace App\Contracts\Services\Analyses;

interface AnalizeTotalVByComponentsServiceInterface
{
    /**
     * Analyze total V by components and return arrays for charting
     *
     * @param array $params
     * @return array
     */
    public function analyze(array $params): array;
}
