<?php
declare(strict_types=1);

namespace DTO;

readonly class StatisticsDTO {

    public int $avgMileage;
    public array $topCountries;
    public int $maxPower;
    public int $minPower;
    public array $gearboxOptions;
    public array $fuelOptions;

    public function __construct(
        int $avgMileage,
        array $topCountries,
        int $maxPower,
        int $minPower,
        array $gearboxOptions,
        array $fuelOptions,
    ) {
        $this->avgMileage = $avgMileage;
        $this->topCountries = $topCountries;
        $this->maxPower = $maxPower;
        $this->minPower = $minPower;
        $this->gearboxOptions = $gearboxOptions;
        $this->fuelOptions = $fuelOptions;
    }
}