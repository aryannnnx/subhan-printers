<?php
// config/database.php


// This should be at the top - load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env');
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $host = getenv('DB_HOST') ?: 'localhost';
            $dbname = getenv('DB_NAME') ?: 'subhan_printers';
            $username = getenv('DB_USER') ?: 'root';
            $password = getenv('DB_PASS') ?: '';
            $port = getenv('DB_PORT') ?: 3306;
            $charset = getenv('DB_CHARSET') ?: 'utf8mb4';
            
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
            
            $this->conn = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true
            ]);
            
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw $e;
        }
    }
    
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection(): PDO {
        return $this->conn;
    }
}

// Make sure THIS function exists
function getDB(): PDO {
    return Database::getInstance()->getConnection();
}



function getDBInstance(): Database {
    return Database::getInstance();
}