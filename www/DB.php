<?php
declare(strict_types=1);

class DB {
    private const HOST = 'mysql';
    private const DB_NAME = 'cardatabase';
    private const USERNAME = 'caruser';
    private const PASSWORD = 'carpassword';

    private static self|null $instance = null;
    private PDO $conn;

    private function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host=" . self::HOST . ";dbname=" . self::DB_NAME,
                self::USERNAME,
                self::PASSWORD
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }
}
