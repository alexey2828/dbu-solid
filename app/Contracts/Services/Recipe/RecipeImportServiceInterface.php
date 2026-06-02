<?php

namespace App\Contracts\Services\Recipe;

interface RecipeImportServiceInterface
{
    /**
     * Process uploaded spreadsheet and import recipe descriptions.
     *
     * @param string $path Absolute path to uploaded file.
     * @return array Import summary stats.
     */
    public function importFromFile(string $path): array;
}
