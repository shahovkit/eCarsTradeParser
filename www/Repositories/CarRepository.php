<?php
declare(strict_types=1);

namespace Repositories;

use DB;
use PDO;

class CarRepository {
    private PDO $db;

    public function __construct() {
        $this->db = DB::getInstance()->getConnection();
    }

    public function insertBatch($batchData) {
        $titles = [
            'external_id',
            'title',
            'first_registration_date',
            'mileage',
            'mark_model_id',
            'gearbox_id',
            'fuel_id',
            'country_id',
            'emission_class_id',
            'engine_size',
            'power',
            'co2_value',
        ];

        $sql = "INSERT INTO cars (" . implode(',', $titles) . ") VALUES ";
        $values = array_merge(...$batchData);


        foreach ($batchData as $row) {
            $sql .= '(' . implode(', ', array_fill(0, count($row), '?')) . '),';
        }
        $sql = rtrim($sql, ',');

        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);

    }

    public function getOrInsertId($table, $column, $value)
    {
//        $cachedId = apcu_fetch($value.$table);
//        if ($cachedId !== false) {
//            return $cachedId;
//        }

        $stmt = $this->db->prepare("SELECT id FROM $table WHERE $column = :value");
        $stmt->execute(['value' => $value]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            //apcu_store($value.$table, $result['id'], 60);
            return $result['id'];
        } else {
            $stmt = $this->db->prepare("INSERT INTO $table ($column) VALUES (:value)");
            $stmt->execute(['value' => $value]);
            return $this->db->lastInsertId();
        }
    }

}