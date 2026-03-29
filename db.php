<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/autoload.php';
use App\Core\Database;

try {
    $pdo = Database::getConnection();
    // Use IF NOT EXISTS equivalent by trying to add column and catching error if exists
    $pdo->exec("ALTER TABLE users ADD COLUMN reset_code VARCHAR(6) NULL, ADD COLUMN reset_expires DATETIME NULL;");
    echo "Migration successful.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "Columns already exist.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
