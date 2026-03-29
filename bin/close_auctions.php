<?php
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/autoload.php';

use App\Core\Database;

/**
 * Auction Closure Cron Script
 * Designed to be executed every 1 minute via server Cron/Scheduled Task.
 */
echo "Initiating Automated Auction Closure Procedure...\n";

$pdo = Database::getConnection();
$pdo->beginTransaction();

try {
    // Pessimistic Locking to ensure multiple cron workers don't process the same auction
    $stmt = $pdo->prepare("SELECT id FROM auctions WHERE status = 'active' AND ends_at <= NOW() FOR UPDATE");
    $stmt->execute();
    $expired = $stmt->fetchAll();
    
    if (count($expired) > 0) {
        $updateStmt = $pdo->prepare("UPDATE auctions SET status = 'sold' WHERE id = :id");
        $logStmt = $pdo->prepare("INSERT INTO audit_logs (action, entity_type, entity_id, details) VALUES (:action, 'Auction', :id, :details)");
        
        foreach ($expired as $auc) {
            $updateStmt->execute(['id' => $auc['id']]);
            // Dispatch domain events here for Winning emails...
            $logStmt->execute([
                'action' => 'Auction Auto-Closed',
                'id' => $auc['id'],
                'details' => 'Auction expired natively via Cron Cloture System.'
            ]);
            echo "LOT #" . $auc['id'] . " processed and secured as SOLD.\n";
        }
    } else {
        echo "0 pending closures detected.\n";
    }
    
    $pdo->commit();
    echo "Procedure completed successfully.\n";
    
} catch (\Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "CRITICAL FAILURE: " . $e->getMessage() . "\n";
}
