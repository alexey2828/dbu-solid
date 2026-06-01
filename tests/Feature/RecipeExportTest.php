<?php

use App\Contracts\Services\RecipeExportServiceInterface;
use App\Http\Controllers\Api\RecipeExportController;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\StreamedResponse;

beforeEach(function () {
    $this->service = Mockery::mock(RecipeExportServiceInterface::class);
});

afterEach(function () {
    Mockery::close();
});

test('recipe export returns streamed response with xlsx content type', function () {
    $spreadsheet = new Spreadsheet;
    $spreadsheet->getActiveSheet()->setCellValue('A1', 'Test');

    $this->service
        ->shouldReceive('buildSpreadsheet')
        ->once()
        ->with('10')
        ->andReturn($spreadsheet);

    $this->service
        ->shouldReceive('buildFilename')
        ->once()
        ->with('10')
        ->andReturn('recipe_export_class_10_test.xlsx');

    $controller = new RecipeExportController($this->service);
    $request = Request::create('/api/recipe-export', 'GET', ['classRecipe' => '10']);

    $response = $controller->export($request);

    expect($response)->toBeInstanceOf(StreamedResponse::class);
    expect($response->headers->get('content-type'))->toBe('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    expect($response->headers->get('content-disposition'))->toContain('attachment; filename=recipe_export_class_10_test.xlsx');
});
