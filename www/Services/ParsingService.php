<?php
declare(strict_types=1);

namespace Services;

use DOMDocument;
use DOMXPath;
use DTO\ParsedCarDTO;

class ParsingService {

    public function fetchCarsData(int $pageNumber, int $perPage): array
    {
        $start = $pageNumber * $perPage;
        $url = "https://ecarstrade.com/future_api.php?request_type=cars&auction_type=search&start=$start&perpage=$perPage&sort=time_left.asc&power_value=kw&auction_type%5B%5D=bid";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($response === false || $info['size_download'] < 15) {
            return [];
        }

        $cleanedText = preg_replace('/^\{\}/', '', trim($response));
        $jsonStrings = '[' . preg_replace('/\}\{/', '},{', $cleanedText) . ']';
        $carsData = json_decode($jsonStrings, true);

        return $carsData ?? [];
    }

    public function HTML2ParsedCarDTO(string $htmlCarCard, int $externalCarId): ParsedCarDTO
    {
        $DOMDocument = new DOMDocument();
        libxml_use_internal_errors(true);
        $DOMDocument->loadHTML($htmlCarCard);
        $xpath = new DOMXPath($DOMDocument);

        $parsedCarData = new ParsedCarDTO(
            $externalCarId,
            trim($xpath->query('//div[contains(@class, "item-title")]//span')->item(0)->textContent ?? ''),
            trim(explode(" - ", $xpath->query('//div[contains(@class, "item-title")]//div[@class="small text-muted"]')->item(0)->textContent)[1] ?? ''),
            trim($xpath->query('//span[@data-original-title="First registration date"]')->item(0)->textContent ?? ''),
            (int) preg_replace('/\D/', '', trim($xpath->query('//span[@data-original-title="Mileage"]')->item(0)->textContent ?? '')),
            trim($xpath->query('//span[@data-original-title="Gearbox"]')->item(0)->textContent ?? ''),
            trim($xpath->query('//span[@data-original-title="Fuel"]')->item(0)->textContent ?? ''),
            (int) explode(' ', trim($xpath->query('//span[@data-original-title="Engine size"]')->item(0)->textContent ?? ''))[0],
            (int) explode(' ', trim($xpath->query('//span[@data-original-title="Power"]')->item(0)->textContent ?? ''))[0],
            trim($xpath->query('//span[@data-original-title="Emission Class"]')->item(0)->textContent ?? ''),
            (int) preg_replace('/\D/', '', trim($xpath->query('//div[@class="item-feature"][.//i[contains(@class, "fa-wind")]]')->item(0)->textContent ?? '')),
            trim(explode(':', $xpath->query('//div[contains(@class, "icon-country-origin")]')->item(0)->getAttribute('data-original-title'))[1] ?? '')
        );

        return $parsedCarData;
    }

    /**
     * @param resource $csvFile
     */
    public function parsedCarDTOAppendToCSV(ParsedCarDTO $DTO, $csvFile): int|false
    {
        return fputcsv($csvFile, [
            $DTO->externalId,
            $DTO->title,
            $DTO->markAndModel,
            $DTO->firstRegistrationDate,
            $DTO->mileage,
            $DTO->gearbox,
            $DTO->fuel,
            $DTO->engineSize,
            $DTO->horsePower,
            $DTO->emissionClass,
            $DTO->co2Value,
            $DTO->countryOfOrigin
        ]);
    }

    /**
     * @param resource $csvFile
     */
    public function fillHeadersCSV($csvFile): int|false
    {
        return fputcsv($csvFile, [
            'external_id',
            'title',
            'mark_and_model',
            'first_registration_date',
            'mileage',
            'gearbox',
            'fuel',
            'engine_size',
            'power',
            'emission_class',
            'co2_value',
            'country_of_origin',
        ]);
    }

    public function parseCars() {
        $totalCars = 350;
        $perPage = $totalCars;
        $lastPage = $totalCars/$perPage;
        $addedCarsCount = 0;
        $csvFile = fopen('cars.csv', 'w');
        $this->fillHeadersCSV($csvFile);

        for ($pageNumber = 0; $pageNumber < $lastPage; $pageNumber++) {
            $fetchedCars = $this->fetchCarsData($pageNumber, $perPage);
            if(count($fetchedCars) === 0){
                break;
            }

            foreach($fetchedCars as $fetchedCar) {
                $htmlCarCard = base64_decode($fetchedCar['result']);
                $parsedCarDTO = $this->HTML2ParsedCarDTO($htmlCarCard, (int) $fetchedCar['car_id']);
                $this->ParsedCarDTOAppendToCSV($parsedCarDTO, $csvFile);
                $addedCarsCount++;
                if ($addedCarsCount >= $totalCars) {
                    break 2;
                }
            }
        }

        fclose($csvFile);
        return $addedCarsCount;
    }
}