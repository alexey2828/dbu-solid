<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\Analyses\AnalizeTotalVServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalizeController extends Controller
{
    protected AnalizeTotalVServiceInterface $service;

    public function __construct(AnalizeTotalVServiceInterface $AnalizeTotalVService)
    {
        $this->service = $AnalizeTotalVService;
    }

    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'timeStart' => 'nullable|date',
            'timeEnd' => 'nullable|date',
            'step' => 'nullable|string|in:hour,day,week,month',
            'bsuCode' => 'nullable|string',
            'car' => 'nullable|string',
            'driver' => 'nullable|string',
            'order' => 'nullable|integer',
            'dispencer' => 'nullable|string',
            'idTtn' => 'nullable|integer',
        ]);

        $result = $this->service->analyze($validated);

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
