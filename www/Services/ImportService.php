<?php
declare(strict_types=1);

namespace Services;

use DateTime;
use Repositories\CarRepository;

class ImportService {

    private CarRepository $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    public function processCsv()
    {
        $batchSize = 1000;
        $handle = fopen('cars.csv', 'r');
        fgetcsv($handle); //skip headers

        while (($csvRow = fgetcsv($handle, 500)) !== false) {
            $relationsIds = $this->prepareRelationsCSVRow($csvRow);
            $batchData[] = $this->prepareDataFromCSV($csvRow, $relationsIds);

            if (count($batchData) >= $batchSize) {
                $this->carRepository->insertBatch($batchData);
            }
        }

        if (count($batchData) > 0) {
            $this->carRepository->insertBatch($batchData);
        }

        fclose($handle);

    }

    public function prepareRelationsCSVRow($csvRow): array
    {
        return [
            'markModelId' => $this->carRepository->getOrInsertId('marks', 'name', $csvRow[2]),
            'gearbox_id' => $this->carRepository->getOrInsertId('gearboxes', 'type', $csvRow[5]),
            'fuel_id' => $this->carRepository->getOrInsertId('fuels', 'type', $csvRow[6]),
            'country_id' => $this->carRepository->getOrInsertId('countries', 'name', $csvRow[11]),
            'emission_class_id' => $this->carRepository->getOrInsertId('emission_classes', 'type', $csvRow[9]),
        ];
    }

    public function prepareDataFromCSV($csvRow, $relationsIds): array
    {
        $dataArray = [
            $csvRow[0],
            $csvRow[1],
            DateTime::createFromFormat('m/Y', str_replace('-', '', $csvRow[3]))->format('Y-m-01'),
            $csvRow[4] ?: null,
            $relationsIds['markModelId'],
            $relationsIds['gearbox_id'],
            $relationsIds['fuel_id'],
            $relationsIds['country_id'],
            $relationsIds['emission_class_id'],
            $csvRow[7] ?: null,
            $csvRow[8] ?: null,
            $csvRow[10] ?: null,
        ];

        return $dataArray;
    }
}