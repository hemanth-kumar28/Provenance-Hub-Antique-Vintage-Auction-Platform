<?php
namespace App\Models;

use App\Core\Database;

/**
 * Represents a Bid placed on an Auction.
 */
class Bid extends Database {
    private int $id;
    private int $auctionId;
    private int $userId;
    private float $amount;
    private string $createdAt;

    public function __construct(int $id, int $auctionId, int $userId, float $amount, string $createdAt) {
        $this->id = $id;
        $this->auctionId = $auctionId;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->createdAt = $createdAt;
    }

    public static function getHighestBidForAuction(int $auctionId): ?self {
        $stmt = self::getConnection()->prepare("SELECT * FROM bids WHERE auction_id = :auction_id ORDER BY amount DESC LIMIT 1");
        $stmt->execute(['auction_id' => $auctionId]);
        $data = $stmt->fetch();
        if ($data) {
            return new self(
                $data['id'], 
                $data['auction_id'], 
                $data['user_id'], 
                (float)$data['amount'], 
                $data['created_at']
            );
        }
        return null;
    }

    public function getAmount(): float { return $this->amount; }
    public function getCreatedAt(): string { return $this->createdAt; }
}
