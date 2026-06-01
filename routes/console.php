<?php

// routes/console.php
use Illuminate\Support\Facades\Artisan;

Artisan::command(
    'db:show-all-tables',
    function (TableDataService $service) {
        
    }
);
