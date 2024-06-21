<?php
declare(strict_types=1);

namespace Controllers;

use Repositories\StatisticRepository;
use Services\StatisticService;

class StatisticController {

    public function getCarStatistics(StatisticService $carService) {
        $statisticRepository = new StatisticRepository();
        $statisticsDTO = $carService->getStatisticsDTO($statisticRepository);

        view('car_statistics', [ 'statisticsDTO' => $statisticsDTO]);
    }
}

