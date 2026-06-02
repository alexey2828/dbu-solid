<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\Analyses\AnalizeTotalVByComponentsServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalizeTotalVByComponentsController extends Controller
{
    protected AnalizeTotalVByComponentsServiceInterface $service;

    public function __construct(AnalizeTotalVByComponentsServiceInterface $service)
    {
        $this->service = $service;
    }

    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'timeStart' => 'nullable|date',
            'timeEnd' => 'nullable|date',
            'step' => 'nullable|string|in:hour,day,week,month',
            'compCode' => 'required|integer',
            'bsuCode' => 'nullable|string',
            'car' => 'nullable|string',
            'driver' => 'nullable|string',
            'order' => 'nullable|integer',
            'dispencer' => 'nullable|string',
            'idTtn' => 'nullable|integer',
            'codePlant' => 'nullable|string',
        ]);

        $result = $this->service->analyze($validated);

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
