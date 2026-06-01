<?php

namespace App\Services;

use App\Contracts\Services\RecipeExportServiceInterface;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RecipeExportService implements RecipeExportServiceInterface
{
    public function buildSpreadsheet(?string $classRecipe = null): Spreadsheet
    {
        $filterByClass = $this->isClassRecipeFilterValid($classRecipe);
        $recipes = $this->fetchRecipes($filterByClass ? $classRecipe : null);
        $recipeComponentCodes = [];

        foreach ($recipes as $recipeId => $recipeData) {
            $recipeComponentCodes[$recipeId] = array_unique(array_map(
                static fn ($component) => $component['codeComponent'], $recipeData['components']
            ));
            sort($recipeComponentCodes[$recipeId], SORT_NUMERIC);
        }

        $allCodes = ['1' => [], '2' => [], '3' => [], '4' => []];
        $codeNames = [];

        foreach ($recipes as $recipeData) {
            foreach ($recipeData['components'] as $component) {
                $code = $component['codeComponent'];
                $firstDigit = substr($code, 0, 1);

                if (in_array($firstDigit, ['1', '2', '3', '4'], true)) {
                    if (! in_array($code, $allCodes[$firstDigit], true)) {
                        $allCodes[$firstDigit][] = $code;
                        $codeNames[$code] = $component['componentName'];
                    }
                }
            }
        }

        foreach ($allCodes as $digit => $codes) {
            sort($codes, SORT_NUMERIC);
            $allCodes[$digit] = $codes;
        }

        if (isset($codeNames['1002'])) {
            $codeNames['1002'] = trim($codeNames['1002']).', %';
        } else {
            $codeNames['1002'] = '1002, %';
        }

        $categories = [
            '4' => $this->toUtf8('Цемент, кг'),
            '3' => $this->toUtf8('Наполнители, кг'),
            '1' => $this->toUtf8('Вода, кг'),
            '2' => $this->toUtf8('ЖХД, кг'),
        ];

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $colIndex = 1;
        $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex).'1', $this->toUtf8('Код'));
        $sheet->mergeCells(Coordinate::stringFromColumnIndex($colIndex).'1:'.Coordinate::stringFromColumnIndex($colIndex).'4');
        $colIndex++;

        $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex).'1', $this->toUtf8('Название рецепта'));
        $sheet->mergeCells(Coordinate::stringFromColumnIndex($colIndex).'1:'.Coordinate::stringFromColumnIndex($colIndex).'4');
        $colIndex++;

        $categoryStartCol = [];

        foreach ($categories as $digit => $categoryName) {
            $codes = $allCodes[$digit];
            $numCodes = count($codes);
            $categoryStartCol[$digit] = $colIndex;

            if ($numCodes > 0) {
                foreach ($codes as $code) {
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex).'2', $this->toUtf8($codeNames[$code]));
                    $sheet->mergeCells(
                        Coordinate::stringFromColumnIndex($colIndex).'2:'.
                        Coordinate::stringFromColumnIndex($colIndex + 1).'2'
                    );
                    $colIndex += 2;
                }

                $colIndex = $categoryStartCol[$digit];

                foreach ($codes as $code) {
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex).'3', $code);
                    $sheet->mergeCells(
                        Coordinate::stringFromColumnIndex($colIndex).'3:'.
                        Coordinate::stringFromColumnIndex($colIndex + 1).'3'
                    );
                    $colIndex += 2;
                }

                $colIndex = $categoryStartCol[$digit];
                for ($i = 0; $i < $numCodes; $i++) {
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex).'4', $this->toUtf8('Зима'));
                    $colIndex++;
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex).'4', $this->toUtf8('Лето'));
                    $colIndex++;
                }

                $endColIndex = $colIndex - 1;
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($categoryStartCol[$digit]).'1', $categoryName);
                $sheet->mergeCells(
                    Coordinate::stringFromColumnIndex($categoryStartCol[$digit]).'1:'.
                    Coordinate::stringFromColumnIndex($endColIndex).'1'
                );
                $colIndex = $endColIndex + 1;
            } else {
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex).'2', '');
                $sheet->mergeCells(
                    Coordinate::stringFromColumnIndex($colIndex).'2:'.
                    Coordinate::stringFromColumnIndex($colIndex + 1).'2'
                );

                $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex).'3', '');
                $sheet->mergeCells(
                    Coordinate::stringFromColumnIndex($colIndex).'3:'.
                    Coordinate::stringFromColumnIndex($colIndex + 1).'3'
                );

                $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex).'4', $this->toUtf8('Зима'));
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex + 1).'4', $this->toUtf8('Лето'));
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex).'1', $categoryName);
                $sheet->mergeCells(
                    Coordinate::stringFromColumnIndex($colIndex).'1:'.
                    Coordinate::stringFromColumnIndex($colIndex + 1).'1'
                );
                $colIndex += 2;
            }
        }

        $sumStartColIndex = $colIndex;
        $sheet->setCellValue(Coordinate::stringFromColumnIndex($sumStartColIndex).'1', $this->toUtf8('Сумма'));
        $sheet->mergeCells(
            Coordinate::stringFromColumnIndex($sumStartColIndex).'1:'.
            Coordinate::stringFromColumnIndex($sumStartColIndex + 1).'1'
        );
        $sheet->setCellValue(Coordinate::stringFromColumnIndex($sumStartColIndex).'2', '');
        $sheet->mergeCells(
            Coordinate::stringFromColumnIndex($sumStartColIndex).'2:'.
            Coordinate::stringFromColumnIndex($sumStartColIndex + 1).'2'
        );
        $sheet->setCellValue(Coordinate::stringFromColumnIndex($sumStartColIndex).'3', '');
        $sheet->mergeCells(
            Coordinate::stringFromColumnIndex($sumStartColIndex).'3:'.
            Coordinate::stringFromColumnIndex($sumStartColIndex + 1).'3'
        );
        $sheet->setCellValue(Coordinate::stringFromColumnIndex($sumStartColIndex).'4', $this->toUtf8('Зима'));
        $sheet->setCellValue(Coordinate::stringFromColumnIndex($sumStartColIndex + 1).'4', $this->toUtf8('Лето'));

        $sumWinterColIndex = $sumStartColIndex;
        $sumSummerColIndex = $sumStartColIndex + 1;

        $rowIndex = 5;
        foreach ($recipes as $recipeData) {
            $grouped = ['1' => [], '2' => [], '3' => [], '4' => []];
            $totalWinterSum = 0;
            $totalSummerSum = 0;

            foreach ($recipeData['components'] as $component) {
                $code = $component['codeComponent'];
                $firstDigit = substr($code, 0, 1);
                if (in_array($firstDigit, ['1', '2', '3', '4'], true)) {
                    $grouped[$firstDigit][$code] = [
                        'summer' => $component['weightSummer'],
                        'winter' => $component['weightWinter'],
                    ];

                    if ($firstDigit !== '2' && $code !== '1002') {
                        $totalWinterSum += $component['weightWinter'];
                        $totalSummerSum += $component['weightSummer'];
                    }
                }
            }

            $sheet->setCellValue(Coordinate::stringFromColumnIndex(1).$rowIndex, $recipeData['fullCode']);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex(2).$rowIndex, $this->toUtf8($recipeData['name']));

            $currentCol = 3;
            foreach ($categories as $digit => $categoryName) {
                $codes = $allCodes[$digit];
                if (count($codes) > 0) {
                    foreach ($codes as $code) {
                        $winterVal = isset($grouped[$digit][$code]['winter']) && $grouped[$digit][$code]['winter'] != 0
                            ? $grouped[$digit][$code]['winter']
                            : '';
                        $summerVal = isset($grouped[$digit][$code]['summer']) && $grouped[$digit][$code]['summer'] != 0
                            ? $grouped[$digit][$code]['summer']
                            : '';

                        $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentCol).$rowIndex, $winterVal);
                        $currentCol++;
                        $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentCol).$rowIndex, $summerVal);
                        $currentCol++;
                    }
                } else {
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentCol).$rowIndex, '');
                    $currentCol++;
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentCol).$rowIndex, '');
                    $currentCol++;
                }
            }

            $sheet->setCellValue(Coordinate::stringFromColumnIndex($sumWinterColIndex).$rowIndex, $totalWinterSum);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($sumSummerColIndex).$rowIndex, $totalSummerSum);
            $rowIndex++;
        }

        $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());
        for ($i = 1; $i <= $highestColumnIndex; $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }

        $highestRow = $sheet->getHighestRow();
        $lastColLetter = Coordinate::stringFromColumnIndex($highestColumnIndex);

        $sheet->getStyle('A1:'.$lastColLetter.'4')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A1:'.$lastColLetter.'4')
            ->getFont()->setBold(true);

        $sheet->getStyle('A1:'.$lastColLetter.'4')
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        if ($highestRow >= 5) {
            $sheet->getStyle('A5:'.$lastColLetter.$highestRow)
                ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('A5:'.$lastColLetter.$highestRow)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return $spreadsheet;
    }

    public function buildFilename(?string $classRecipe = null): string
    {
        if ($this->isClassRecipeFilterValid($classRecipe)) {
            return 'recipe_export_class_'.$classRecipe.'_'.date('Y-m-d_H-i-s').'.xlsx';
        }

        return 'recipe_export_all_'.date('Y-m-d_H-i-s').'.xlsx';
    }

    protected function fetchRecipes(?string $classRecipe): array
    {
        $query = DB::table('recipe as r')
            ->select([
                'r.id',
                'r.code',
                'r.classRecipe',
                'r.name',
                'rd.codeComponent',
                'rd.weightSummer',
                'rd.weightWinter',
                'c.name as componentName',
            ])
            ->leftJoin('recipedescription as rd', 'r.id', '=', 'rd.codeRecipe')
            ->leftJoin('comp as c', function ($join) {
                $join->on(DB::raw('rd.codeComponent COLLATE utf8mb4_unicode_ci'), '=', DB::raw('c.code COLLATE utf8mb4_unicode_ci'));
            })
            ->orderBy('r.id')
            ->orderBy('rd.id');

        if ($this->isClassRecipeFilterValid($classRecipe)) {
            $query->where('r.classRecipe', $classRecipe);
        }

        $rows = $query->get();

        $recipes = [];

        foreach ($rows as $row) {
            $recipeId = $row->id;
            if (! isset($recipes[$recipeId])) {
                $recipes[$recipeId] = [
                    'fullCode' => trim(((string) $row->classRecipe).' '.((string) $row->code)),
                    'name' => $this->fixBrokenUtf8($row->name),
                    'components' => [],
                ];
            }

            if ($row->codeComponent !== null) {
                $componentCode = (string) $row->codeComponent;
                $componentName = $this->fixBrokenUtf8($row->componentName);
                if ($componentName === '') {
                    $componentName = $componentCode;
                }

                $recipes[$recipeId]['components'][] = [
                    'codeComponent' => $componentCode,
                    'componentName' => $componentName,
                    'weightSummer' => floatval($row->weightSummer ?? 0),
                    'weightWinter' => floatval($row->weightWinter ?? 0),
                ];
            }
        }

        return $recipes;
    }

    protected function isClassRecipeFilterValid(?string $classRecipe): bool
    {
        return is_string($classRecipe) && $classRecipe !== '' && preg_match('/^\d+$/', $classRecipe) === 1;
    }

    protected function toUtf8(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        if (mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        $encoding = mb_detect_encoding($value, ['UTF-8', 'Windows-1251', 'KOI8-R', 'ISO-8859-5'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $converted = mb_convert_encoding($value, 'UTF-8', $encoding);
            if ($converted !== false && mb_check_encoding($converted, 'UTF-8')) {
                return $converted;
            }
        }

        $converted = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
        if ($converted !== false && mb_check_encoding($converted, 'UTF-8')) {
            return $converted;
        }

        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }

    protected function fixBrokenUtf8(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        if (mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        $fixed = @iconv('latin1', 'UTF-8//IGNORE', $value);
        if ($fixed !== false && preg_match('/[а-яА-Я]/u', $fixed)) {
            return $fixed;
        }

        return $value;
    }
}
