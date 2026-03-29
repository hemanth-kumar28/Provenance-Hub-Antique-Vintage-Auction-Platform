<?php
namespace App\Traits;

/**
 * Reusable logic for writing secure audit logs to a physical file and optionally DB.
 */
trait AuditLogging {
    
    /**
     * Writes an audit entry.
     * Uses null coalescing operator (??) and magic constants.
     */
    protected function logAction(string $action, ?string $entityType = null, ?int $entityId = null, string $details = ''): bool {
        $timestamp = date('Y-m-d H:i:s');
        
        $entityTypeStr = $entityType ?? 'System';
        $entityIdStr = $entityId ?? 'N/A';

        $logEntry = sprintf(
            "[%s] ACTION: %s | ENTITY: %s | ID: %s | DETAILS: %s\n",
            $timestamp,
            $action,
            $entityTypeStr,
            $entityIdStr,
            $details
        );

        if (defined('LOG_PATH')) {
            if (!file_exists(dirname(LOG_PATH))) {
                mkdir(dirname(LOG_PATH), 0755, true);
            }
            
            try {
                file_put_contents(LOG_PATH, $logEntry, FILE_APPEND | LOCK_EX);
                return true;
            } catch (\Exception $e) {
                error_log("Failed writing audit log in " . __FILE__ . " on line " . __LINE__);
                return false;
            }
        }
        return false;
    }
}
