<?php

namespace App\Services;

use App\Contracts\Services\RecipeImportServiceInterface;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class RecipeImportService implements RecipeImportServiceInterface
{
    public function importFromFile(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        $updatedRecipes = 0;
        $skippedRecipes = 0;
        $updatedComponents = 0;
        $skippedDetails = [];

        DB::beginTransaction();
        try {
            for ($row = 5; $row <= $highestRow; $row++) {
                $fullCode = trim((string)$sheet->getCell('A' . $row)->getValue());
                if ($fullCode === '') {
                    continue;
                }

                [$classRecipe, $code] = $this->splitFullCode($fullCode);
                if ($classRecipe === null || $code === null) {
                    continue;
                }

                $recipeId = $this->findRecipeId($classRecipe, $code);
                if (! $recipeId) {
                    $skippedRecipes++;
                    $skippedDetails[] = compact('fullCode', 'classRecipe', 'code');
                    continue;
                }
                $updatedRecipes++;

                $components = [];
                $colIndex = 3;
                while ($colIndex <= $highestColumnIndex) {
                    $compCode = trim((string)$sheet->getCell(Coordinate::stringFromColumnIndex($colIndex) . '3')->getValue());
                    if (! preg_match('/^\d+$/', $compCode)) {
                        break;
                    }
                    $winterVal = $sheet->getCell(Coordinate::stringFromColumnIndex($colIndex) . $row)->getValue();
                    $summerVal = $sheet->getCell(Coordinate::stringFromColumnIndex($colIndex + 1) . $row)->getValue();
                    $winter = $this->toFloat($winterVal);
                    $summer = $this->toFloat($summerVal);
                    if ($winter != 0 || $summer != 0) {
                        $components[] = ['code' => $compCode, 'winter' => $winter, 'summer' => $summer];
                    }
                    $colIndex += 2;
                }

                $importedCodes = [];
                foreach ($components as $comp) {
                    DB::table('recipedescription')
                        ->updateOrInsert([
                            'codeRecipe' => $recipeId,
                            'codeComponent' => $comp['code']
                        ], [
                            'weightSummer' => $comp['summer'],
                            'weightWinter' => $comp['winter']
                        ]);

                    $importedCodes[] = $comp['code'];
                    $updatedComponents++;
                }

                if (! empty($importedCodes)) {
                    DB::table('recipedescription')
                        ->where('codeRecipe', $recipeId)
                        ->whereNotIn('codeComponent', $importedCodes)
                        ->delete();
                } else {
                    DB::table('recipedescription')
                        ->where('codeRecipe', $recipeId)
                        ->delete();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'success' => true,
            'stats' => [
                'updated_recipes' => $updatedRecipes,
                'skipped_recipes' => $skippedRecipes,
                'updated_components' => $updatedComponents,
                'skipped_details' => $skippedDetails,
            ],
        ];
    }

    protected function splitFullCode(string $fullCode): array
    {
        if (preg_match('/^(\d)(\d+)$/', $fullCode, $m)) {
            return [$m[1], $m[2]];
        }
        return [null, null];
    }

    protected function findRecipeId(string $classRecipe, string $code): ?int
    {
        $combinations = [
            [$classRecipe, $code],
            [(int)$classRecipe, (int)$code],
            [(int)$classRecipe, $code],
            [$classRecipe, (int)$code],
        ];

        foreach ($combinations as $combo) {
            [$cr, $c] = $combo;
            $row = DB::table('recipe')
                ->where('classRecipe', $cr)
                ->where('code', $c)
                ->first();
            if ($row) {
                return (int)$row->id;
            }
        }

        return null;
    }

    protected function toFloat($value): float
    {
        if (is_numeric($value)) return (float)$value;
        $v = trim((string)$value);
        $v = str_replace(',', '.', $v);
        return is_numeric($v) ? (float)$v : 0.0;
    }
}
