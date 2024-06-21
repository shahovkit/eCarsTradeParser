<?php
declare(strict_types=1);

namespace Utils;

use DB;
use PDOException;

class Migration {
    public function migrate(){
        try {
            $pdo = DB::getInstance()->getConnection();

            $sql = "
                CREATE TABLE IF NOT EXISTS marks (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    name VARCHAR(255) NOT NULL UNIQUE 
                );
            
                CREATE TABLE IF NOT EXISTS gearboxes (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    type VARCHAR(50) NOT NULL UNIQUE 
                );
            
                CREATE TABLE IF NOT EXISTS fuels (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    type VARCHAR(50) NOT NULL UNIQUE 
                );
            
                CREATE TABLE IF NOT EXISTS countries (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    name VARCHAR(50) NOT NULL UNIQUE 
                );
            
                CREATE TABLE IF NOT EXISTS emission_classes (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    type VARCHAR(50) NOT NULL UNIQUE 
                );
            
                CREATE TABLE IF NOT EXISTS cars (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    external_id INT UNIQUE,
                    title VARCHAR(255),
                    mark_model_id INT,
                    first_registration_date DATE,
                    mileage INT,
                    gearbox_id INT,
                    fuel_id INT,
                    engine_size INT,
                    power INT,
                    emission_class_id INT,
                    co2_value INT,
                    country_id INT,
                    FOREIGN KEY (emission_class_id) REFERENCES emission_classes(id),
                    FOREIGN KEY (mark_model_id) REFERENCES marks(id),
                    FOREIGN KEY (gearbox_id) REFERENCES gearboxes(id),
                    FOREIGN KEY (fuel_id) REFERENCES fuels(id),
                    FOREIGN KEY (country_id) REFERENCES countries(id)
                );

                CREATE INDEX idx_cars_mark_model_id ON cars(mark_model_id);
                CREATE INDEX idx_cars_gearbox_id ON cars(gearbox_id);
                CREATE INDEX idx_cars_fuel_id ON cars(fuel_id);
                CREATE INDEX idx_cars_emission_class_id ON cars(emission_class_id);
                CREATE INDEX idx_cars_country_id ON cars(country_id);
                CREATE INDEX idx_cars_first_registration_date ON cars(first_registration_date);
                CREATE INDEX idx_cars_mileage ON cars(mileage);
                CREATE INDEX idx_cars_engine_size ON cars(engine_size);
                CREATE INDEX idx_cars_power ON cars(power);
                CREATE INDEX idx_cars_co2_value ON cars(co2_value);
            ";
            $pdo->exec($sql);
        } catch (PDOException $e) {
                http_response_code(500);
        }
    }
}