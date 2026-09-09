<?php
// app/config/database.php

date_default_timezone_set('Asia/Jakarta');

class Database {
    private static $instance = null;
    private $pdo;

    private $host = 'localhost';
    private $db   = 'gudang_tkj';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';

    private function __construct() {
        date_default_timezone_set('Asia/Jakarta');
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ];

        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        try {
            $this->pdo = new PDO($dsn, 'gudang_user', 'GudangPass123!', $options);
            $this->pdo->exec("SET time_zone = '+07:00'");
        } catch (PDOException $e) {
            try {
                $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
                $this->pdo->exec("SET time_zone = '+07:00'");
            } catch (PDOException $e2) {
                $this->pdo = null;
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
