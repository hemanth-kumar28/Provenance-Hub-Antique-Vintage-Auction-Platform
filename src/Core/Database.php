<?php
namespace App\Core;

/**
 * Ensures strict PDO Singleton connection execution.
 */
abstract class Database {
    private static ?\PDO $connection = null;

    /**
     * Initializes and returns a PDO connection adhering to global constraints.
     */
    public static function getConnection(): \PDO {
        if (self::$connection === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            try {
                self::$connection = new \PDO($dsn, DB_USER, DB_PASS, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (\PDOException $e) {
                // Using Magic Constants for precise error location logging
                error_log("Database Connection Failed: " . $e->getMessage() . " in " . __FILE__ . " on line " . __LINE__);
                die("Critical System Error: Unable to establish database connection.");
            }
        }
        return self::$connection;
    }
}
