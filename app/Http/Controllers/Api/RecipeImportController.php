<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\RecipeImportServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RecipeImportController
{
    public function __construct(private RecipeImportServiceInterface $service) {}

    public function import(Request $request): JsonResponse
    {
        if (! $request->isMethod('post')) {
            return response()->json(['error' => 'Method not allowed. Use POST.'], 405);
        }

        if (! $request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('file');
        if (! $file->isValid()) {
            return response()->json(['error' => 'Upload error'], 400);
        }

        $mime = $file->getMimeType();
        if (! in_array($mime, ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'], true)) {
            return response()->json(['error' => 'Invalid file type. Please upload an .xlsx file.'], 400);
        }

        try {
            $path = $file->getRealPath();
            $result = $this->service->importFromFile($path);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Import failed', 'message' => $e->getMessage()], 500);
        }
    }
}
