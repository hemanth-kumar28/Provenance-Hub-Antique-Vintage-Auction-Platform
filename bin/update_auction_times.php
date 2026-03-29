<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/Core/Database.php';

use App\Core\Database;

$pdo = Database::getConnection();

// Fetch all active auctions
$stmt = $pdo->query("SELECT id FROM auctions WHERE status = 'active'");
$auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$updated = 0;
foreach ($auctions as $a) {
    // Generate random days between 3 and 14
    $randomDays = rand(3, 14);
    $randomHours = rand(0, 23);
    $randomMinutes = rand(0, 59);
    
    // Create the future timestamp
    $futureTs = time() + ($randomDays * 86400) + ($randomHours * 3600) + ($randomMinutes * 60);
    $newEndsAt = date('Y-m-d H:i:s', $futureTs);
    
    $uStmt = $pdo->prepare("UPDATE auctions SET ends_at = :ea WHERE id = :id");
    $uStmt->execute(['ea' => $newEndsAt, 'id' => $a['id']]);
    $updated++;
}

echo "Successfully updated $updated active auctions to end between 3 and 14 days from now.\n";
