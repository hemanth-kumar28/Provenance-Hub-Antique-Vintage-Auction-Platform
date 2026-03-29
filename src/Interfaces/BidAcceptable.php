<?php
namespace App\Interfaces;

/**
 * Interface that entities explicitly implement to accept monetary bids.
 */
interface BidAcceptable {
    /**
     * Attempts to place a bid.
     */
    public function placeBid(int $userId, float $amount): array;
    
    /**
     * Retrieves the current highest bid.
     */
    public function getCurrentBid(): float;
    
    /**
     * Determines if the auction is still open.
     */
    public function isBiddingOpen(): bool;
    
    /**
     * Returns the minimum acceptable next bid amount.
     */
    public function getMinimumNextBid(): float;
}
