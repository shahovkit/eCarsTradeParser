<?php
include 'autoload.php';
include 'Utils/view_includer.php';

use Controllers\InitializationController;
use Controllers\StatisticController;
use Controllers\CarImportController;
use Repositories\CarRepository;
use Services\StatisticService;
use Services\ImportService;
use Services\ParsingService;
use Utils\Migration;

$controllerName = isset($_GET['controller']) ? htmlspecialchars($_GET['controller']) : 'CarController';
$methodName = isset($_GET['action']) ? htmlspecialchars($_GET['action']) : 'getCarStatistics';

$controllerWithAction = $controllerName . '/' . $methodName;

switch ($controllerWithAction){

    case 'CarController/getCarStatistics':
        $carService = new StatisticService();
        (new StatisticController())->getCarStatistics($carService);
    break;

    case 'CarImportController/parseToCSV':
        $parsingService = new ParsingService();
        (new CarImportController())->parseToCSV($parsingService);
    break;

    case 'CarImportController/importFromCSV':
        $carRepository = new CarRepository();
        $importService = new ImportService($carRepository);
        (new CarImportController())->importFromCSV($importService);
    break;

    case 'Migration/migrate':
        (new Migration())->migrate();
    break;

    case 'InitializationController/initialize':
        (new InitializationController())->initialize();
    break;
}



