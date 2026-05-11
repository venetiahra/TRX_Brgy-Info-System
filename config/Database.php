<?php
require_once __DIR__ . '/app.php';

class Database
{
    private $host = 'localhost';
    private $db_name = 'barangay_db';
    private $username = 'root';
    private $password = '';

    public function connect(): PDO
    {
        try {
            return new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            exit('Database connection failed: ' . $e->getMessage());
        }
    }
}
?>
