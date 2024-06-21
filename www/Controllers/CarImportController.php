<?php
declare(strict_types=1);

namespace Controllers;

use Services\ImportService;
use Services\ParsingService;
use Throwable;

class CarImportController {

    public function parseToCSV(ParsingService $parsingService)
    {

            $parsingService->parseCars();

    }

    public function importFromCSV(ImportService $importService)
    {
        $importService->processCsv();
    }
}