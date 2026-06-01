<?php

namespace App\Contracts\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

interface RecipeExportServiceInterface
{
    /**
     * Build spreadsheet for recipe export.
     */
    public function buildSpreadsheet(?string $classRecipe = null): Spreadsheet;

    /**
     * Get filename for recipe export download.
     */
    public function buildFilename(?string $classRecipe = null): string;
}
