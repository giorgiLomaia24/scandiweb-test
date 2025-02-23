<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;
use Exception;

/**
 * Class Database
 *
 * Singleton class responsible for handling database connections.
 */
class Database
{
    /**
     * @var PDO|null Holds the database connection instance.
     */
    private static ?PDO $connection = null;

    /**
     * Establishes a database connection using PDO.
     *
     * @return PDO The database connection instance.
     * @throws Exception If connection fails.
     */
    public static function connect(): PDO
    {
        if (self::$connection === null) {
            $host = $_ENV['DB_HOST'];
            $db   = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];
            $port = $_ENV['DB_PORT'];
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$connection = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                throw new Exception("Database connection error. Please try again later.");
            }
        }

        return self::$connection;
    }
}
