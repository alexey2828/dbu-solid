<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\RecipeExportServiceInterface;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecipeExportController
{
    public function __construct(private RecipeExportServiceInterface $exportService) {}

    public function export(Request $request): StreamedResponse
    {
        $classRecipe = $request->query('classRecipe');
        $spreadsheet = $this->exportService->buildSpreadsheet($classRecipe);
        $filename = $this->exportService->buildFilename($classRecipe);

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            $filename,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'max-age=0, must-revalidate, private',
                'Pragma' => 'public',
            ]
        );
    }
}
