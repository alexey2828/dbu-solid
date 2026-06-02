<?php

use App\Contracts\Services\Recipe\RecipeImportServiceInterface;
use App\Http\Controllers\Api\RecipeImportController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

beforeEach(function () {
    $this->service = Mockery::mock(RecipeImportServiceInterface::class);
});

afterEach(function () {
    Mockery::close();
});

test('recipe import calls service and returns json summary', function () {
    $spreadsheet = new Spreadsheet;
    $spreadsheet->getActiveSheet()->setCellValue('A1', 'test');

    $tmp = sys_get_temp_dir().DIRECTORY_SEPARATOR.'test_recipe_import.xlsx';
    $writer = new Xlsx($spreadsheet);
    $writer->save($tmp);

    $uploaded = new UploadedFile($tmp, 'import.xlsx', null, null, true);

    $this->service
        ->shouldReceive('importFromFile')
        ->once()
        ->with($uploaded->getRealPath())
        ->andReturn([
            'success' => true,
            'stats' => ['updated_recipes' => 1],
        ]);

    $controller = new RecipeImportController($this->service);

    $request = Request::create('/api/recipe-import', 'POST', [], [], ['file' => $uploaded]);

    $response = $controller->import($request);

    expect($response)->toBeInstanceOf(JsonResponse::class);
    $data = $response->getData(true);
    expect($data['success'])->toBeTrue();
    expect($data['stats']['updated_recipes'])->toBe(1);

    // cleanup
    @unlink($tmp);
});
