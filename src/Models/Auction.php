<?php
namespace App\Models;

use App\Core\Database;
use App\Interfaces\BidAcceptable;
use App\Traits\AuditLogging;

/**
 * Auction Model handling core bidding logic, pessimistic locking, and traits.
 */
class Auction extends Database implements BidAcceptable {
    use AuditLogging;

    private int $id;
    private string $title;
    private float $startingPrice;
    private float $currentBid;
    private string $endsAt;
    private string $status;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->startingPrice = (float)($data['starting_price'] ?? 0.0);
        $this->currentBid = (float)($data['current_bid'] ?? 0.0);
        $this->endsAt = $data['ends_at'];
        $this->status = $data['status'];
    }

    public static function findById(int $id): ?self {
        $stmt = self::getConnection()->prepare("SELECT * FROM auctions WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        if ($data) {
            return new self($data);
        }
        return null;
    }

    /**
     * Executes bidding logic inside a PDO transaction.
     */
    public function placeBid(int $userId, float $amount): array {
        if (!$this->isBiddingOpen()) {
            return ['success' => false, 'error' => 'Auction is no longer active.'];
        }

        if ($amount < $this->getMinimumNextBid()) {
            return ['success' => false, 'error' => 'Bid amount must be at least the minimum next bid.'];
        }

        $pdo = self::getConnection();
        try {
            $pdo->beginTransaction();

            // Pessimistic Locking: lock the row for update so no concurrent bids can interfere
            $stmt = $pdo->prepare("SELECT current_bid, status, ends_at FROM auctions WHERE id = :id FOR UPDATE");
            $stmt->execute(['id' => $this->id]);
            $auctionData = $stmt->fetch();

            if ($auctionData['status'] !== 'active' || strtotime($auctionData['ends_at']) < time()) {
                $pdo->rollBack();
                return ['success' => false, 'error' => 'Auction has closed.'];
            }

            $currentBid = (float)$auctionData['current_bid'];
            $reqNext = $currentBid > 0 ? $currentBid + ($currentBid * 0.05) : $this->startingPrice;

            if ($amount < $reqNext) {
                $pdo->rollBack();
                return ['success' => false, 'error' => 'A higher bid was just placed. Please increase your bid.'];
            }

            // ANTI-SNIPING LOGIC: Extend by 5 minutes if bid is placed within last 5 minutes
            $timeRemaining = strtotime($auctionData['ends_at']) - time();
            if ($timeRemaining < 300 && $timeRemaining > 0) {
                $newEndsAt = date('Y-m-d H:i:s', strtotime($auctionData['ends_at']) + 300); // Add 300s
                $extendStmt = $pdo->prepare("UPDATE auctions SET ends_at = :new_end WHERE id = :id");
                $extendStmt->execute(['new_end' => $newEndsAt, 'id' => $this->id]);
                $this->endsAt = $newEndsAt; // update local state
            }

            // Insert new bid
            $insertBid = $pdo->prepare("INSERT INTO bids (auction_id, user_id, amount) VALUES (:auction_id, :user_id, :amount)");
            $insertBid->execute([
                'auction_id' => $this->id,
                'user_id' => $userId,
                'amount' => $amount
            ]);

            // Update auction current bid
            $updateAuction = $pdo->prepare("UPDATE auctions SET current_bid = :amount WHERE id = :id");
            $updateAuction->execute([
                'amount' => $amount,
                'id' => $this->id
            ]);

            $pdo->commit();
            
            // Log Action using Trait
            $this->logAction("Placed Bid", "Auction", $this->id, "User ID: {$userId} Bid Amount: {$amount}");

            $this->currentBid = $amount;
            return ['success' => true, 'message' => 'Bid placed successfully.', 'new_bid' => $amount];

        } catch (\Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Bidding transaction failed: " . $e->getMessage() . " in " . __FILE__ . " on line " . __LINE__);
            return ['success' => false, 'error' => 'A system error occurred while placing your bid.'];
        }
    }

    public function getCurrentBid(): float {
        return $this->currentBid;
    }

    public function isBiddingOpen(): bool {
        return $this->status === 'active' && strtotime($this->endsAt) > time();
    }

    public function getMinimumNextBid(): float {
        if ($this->currentBid > 0) {
            return $this->currentBid + ($this->currentBid * 0.05);
        }
        return $this->startingPrice;
    }
    
    /**
     * Requirement: Spaceship Operator sorting (`<=>`) implemented as a callback.
     */
    public static function sortAuctionsByPrice(array &$auctions, string $dir = 'ASC'): void {
        usort($auctions, function(Auction $a, Auction $b) use ($dir) {
            if ($dir === 'ASC') {
                return $a->getCurrentBid() <=> $b->getCurrentBid();
            }
            return $b->getCurrentBid() <=> $a->getCurrentBid();
        });
    }
    
    public function toArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'current_bid' => $this->currentBid,
            'status' => $this->status,
            'ends_at' => $this->endsAt
        ];
    }
}
