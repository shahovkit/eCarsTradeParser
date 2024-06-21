<?php
declare(strict_types=1);
    
namespace Repositories;

use DB;
use PDO;

class StatisticRepository {

    private PDO $db;

    public function __construct()
    {
        $this->db = DB::getInstance()->getConnection();
    }

    public function getAVGMileage(): int
    {
        $sql = "
            SELECT AVG(Mileage) as avg_mileage 
            FROM cars
        ";

        return (int) $this->db->query($sql)->fetchColumn();
    }
    public function getTop3CountriesOfOrigin(): array
    {
        $sql = "
            SELECT countries.name, COUNT(cars.id) as count 
            FROM countries 
            join cars
                on cars.country_id = countries.id
            GROUP BY countries.name 
            ORDER BY count DESC 
            LIMIT 3
        ";

        return  $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getMaxMinPower(): array
    {
        $sql = "
            SELECT MAX(power) as max_power, MIN(power) as min_power
            FROM cars
        ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];
    }
    public function getGearboxTypes(): array
    {
        $sql = "
            SELECT type 
            FROM gearboxes
        ";

        return  $this->db->query($sql)->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    public function getFuelTypes(): array
    {
        $sql = "
            SELECT type 
            FROM fuels
        ";
        return  $this->db->query($sql)->fetchAll(PDO::FETCH_COLUMN, 0);
    }
}