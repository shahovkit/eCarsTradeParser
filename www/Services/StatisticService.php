<?php
declare(strict_types=1);

namespace Services;

use DTO\StatisticsDTO;
use Repositories\StatisticRepository;
use Throwable;

class StatisticService {

    public function getStatisticsDTO(StatisticRepository $statisticRepository): StatisticsDTO {
        try {
            $avgMileage = $statisticRepository->getAVGMileage();
            $topCountries = $statisticRepository->getTop3CountriesOfOrigin();
            $power = $statisticRepository->getMaxMinPower();
            $gearboxOptions = $statisticRepository->getGearboxTypes();
            $fuelOptions = $statisticRepository->getFuelTypes();
        } finally {
            if(empty($avgMileage)){
                header("Location: /?controller=InitializationController&action=initialize");
                exit;
            }
        }

        $DTO = new StatisticsDTO(
            $avgMileage,
            $topCountries,
            $power['max_power'],
            $power['min_power'],
            $gearboxOptions,
            $fuelOptions,
        );

        return $DTO;
    }
}