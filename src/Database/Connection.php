<?php
namespace CoreCRM\Database;

class Connection {
    private \PDO $pdo;

    public function __construct(array $config = []) {
        $host = $config['host'] ?? getenv('DB_HOST') ?: '127.0.0.1';
        $port = $config['port'] ?? getenv('DB_PORT') ?: '3306';
        $db   = $config['database'] ?? getenv('DB_DATABASE') ?: 'core_crm';
        $user = $config['user'] ?? getenv('DB_USERNAME') ?: 'root';
        $pass = $config['password'] ?? getenv('DB_PASSWORD') ?: '';

        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
        $this->pdo = new \PDO($dsn, $user, $pass, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
    }

    public function pdo(): \PDO { return $this->pdo; }
}