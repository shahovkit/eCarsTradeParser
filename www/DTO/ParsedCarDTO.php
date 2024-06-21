<?php
declare(strict_types=1);

namespace DTO;

readonly class ParsedCarDTO {

    public int $externalId;
    public string $title;
    public string $markAndModel;
    public string $firstRegistrationDate;
    public int $mileage;
    public string $gearbox;
    public string $fuel;
    public int $engineSize;
    public int $horsePower;
    public string $emissionClass;
    public int $co2Value;
    public string $countryOfOrigin;

    public function __construct(
        int $externalId,
        string $title,
        string $markAndModel,
        string $firstRegistrationDate,
        int $mileage,
        string $gearbox,
        string $fuel,
        int $engineSize,
        int $horsePower,
        string $emissionClass,
        int $co2Value,
        string $countryOfOrigin)
    {
        $this->externalId = $externalId;
        $this->title = $title;
        $this->markAndModel = $markAndModel;
        $this->firstRegistrationDate = $firstRegistrationDate;
        $this->mileage = $mileage;
        $this->gearbox = $gearbox;
        $this->fuel = $fuel;
        $this->engineSize = $engineSize;
        $this->horsePower = $horsePower;
        $this->emissionClass = $emissionClass;
        $this->co2Value = $co2Value;
        $this->countryOfOrigin = $countryOfOrigin;
    }

}