<?php
namespace App\Controllers;

use App\Models\Auction;
use App\Core\Database;

class ApiController {
    public function placeBid() {
        header('Content-Type: application/json');
        
        $auctionId = (int)($_POST['auction_id'] ?? 0);
        $amount = (float)($_POST['amount'] ?? 0);
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            echo json_encode(['success' => false, 'error' => 'Authentication required to participate in bidding.']);
            exit;
        }

        $userRole = $_SESSION['role'] ?? 'collector';
        if (in_array($userRole, ['admin', 'curator'])) {
            echo json_encode(['success' => false, 'error' => 'Curators and Administrators are not permitted to participate in bidding.']);
            exit;
        }

        if (!$auctionId || !$amount) {
            echo json_encode(['success' => false, 'error' => 'Invalid auction parameters.']);
            exit;
        }

        $auction = Auction::findById($auctionId);
        if (!$auction) {
            echo json_encode(['success' => false, 'error' => 'Requested Auction could not be located.']);
            exit;
        }

        $result = $auction->placeBid($userId, $amount);
        echo json_encode($result);
        exit;
    }

    public function filterAuctions() {
        header('Content-Type: application/json');
        
        $pdo = Database::getConnection();
        
        $category = $_GET['category'] ?? 'All Collections';
        $era = $_GET['era'] ?? 'All Eras';
        
        $params = [];
        $conditions = [];
        
        if ($category !== 'All Collections') {
            $conditions[] = 'category = :category';
            $params['category'] = $category;
        }
        if ($era !== 'All Eras') {
            $conditions[] = 'era = :era';
            $params['era'] = $era;
        }
        
        $whereSql = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) . ' AND status="active"' : 'WHERE status="active"';
        
        $stmt = $pdo->prepare("SELECT * FROM auctions $whereSql ORDER BY ends_at ASC");
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        
        $response = [];
        foreach ($results as $row) {
            $response[] = [
                'id' => $row['id'],
                'title' => htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'),
                'current_bid' => (float)$row['current_bid'],
                'ends_at' => $row['ends_at'],
                'image_url' => htmlspecialchars($row['image_url'] ?? '', ENT_QUOTES, 'UTF-8'),
                'era' => htmlspecialchars($row['era'] ?? '', ENT_QUOTES, 'UTF-8'),
                'category' => htmlspecialchars($row['category'] ?? '', ENT_QUOTES, 'UTF-8')
            ];
        }
        
        echo json_encode([
            'success' => true,
            'data' => $response
        ]);
        exit;
    }

    public function searchSuggestions() {
        header('Content-Type: application/json');
        
        $pdo = Database::getConnection();
        $q = trim($_GET['q'] ?? '');
        
        if (strlen($q) < 1) {
            echo json_encode(['success' => true, 'data' => []]);
            exit;
        }

        $stmt = $pdo->prepare("SELECT id, title, current_bid, image_url, ends_at FROM auctions WHERE status = 'active' AND title LIKE :q ORDER BY current_bid DESC LIMIT 5");
        $stmt->execute(['q' => '%' . $q . '%']);
        $results = $stmt->fetchAll();
        
        $response = [];
        foreach ($results as $row) {
            $response[] = [
                'id' => $row['id'],
                'title' => htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'),
                'current_bid' => (float)$row['current_bid'],
                'image_url' => htmlspecialchars($row['image_url'] ?? '', ENT_QUOTES, 'UTF-8'),
                'ends_at' => $row['ends_at']
            ];
        }
        
        echo json_encode(['success' => true, 'data' => $response]);
        exit;
    }

    public function getBids() {
        header('Content-Type: application/json');
        
        $auctionId = (int)($_GET['id'] ?? 0);
        if (!$auctionId) {
            echo json_encode(['success' => false, 'error' => 'Invalid auction ID.']);
            exit;
        }

        $pdo = Database::getConnection();
        
        // Get current bid info
        $stmt = $pdo->prepare("SELECT current_bid, status, ends_at FROM auctions WHERE id = :id");
        $stmt->execute(['id' => $auctionId]);
        $auction = $stmt->fetch();
        
        if (!$auction) {
            echo json_encode(['success' => false, 'error' => 'Auction not found.']);
            exit;
        }

        // Get recent bids
        $bStmt = $pdo->prepare("SELECT b.amount, b.created_at, u.username FROM bids b JOIN users u ON b.user_id = u.id WHERE b.auction_id = :id ORDER BY b.amount DESC LIMIT 5");
        $bStmt->execute(['id' => $auctionId]);
        $bids = $bStmt->fetchAll();

        $formattedBids = [];
        foreach ($bids as $bid) {
            $formattedBids[] = [
                'username' => htmlspecialchars(substr($bid['username'], 0, 3)) . '***',
                'amount' => (float)$bid['amount'],
                'created_at' => $bid['created_at']
            ];
        }
        
        echo json_encode([
            'success' => true,
            'current_bid' => (float)$auction['current_bid'],
            'status' => $auction['status'],
            'ends_at' => $auction['ends_at'],
            'bids' => $formattedBids
        ]);
        exit;
    }
}
