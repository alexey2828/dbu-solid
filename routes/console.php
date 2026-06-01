<?php

// routes/console.php
use App\Services\TableDataService;
use Illuminate\Support\Facades\Artisan;

Artisan::command(
    'db:show-all-tables',
    function (TableDataService $service) {
        $tables = $service->getAllTablesData();

        foreach ($tables as $tableName => $data) {
            $this->info("Table: {$tableName}");
            $this->table(
                array_keys($data->first()?->toArray() ?? []),
                $data->toArray()
            );
            $this->newLine();
        }
    }
);
