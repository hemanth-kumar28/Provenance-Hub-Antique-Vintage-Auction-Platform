<?php
namespace App\Controllers;

use App\Core\Database;

class PageController {
    public function home() {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM auctions WHERE status = 'active' ORDER BY current_bid DESC LIMIT 3");
        $stmt->execute();
        $liveAuctions = $stmt->fetchAll();
        require_once BASE_PATH . '/views/home.php';
    }

    public function marketplace() {
        $pdo = Database::getConnection();
        
        $q = $_GET['q'] ?? '';
        $category = $_GET['category'] ?? '';
        $era = $_GET['era'] ?? '';
        $price = $_GET['price'] ?? '';
        
        $conditions = ["status = 'active'"];
        $params = [];
        
        if ($q !== '') {
            $conditions[] = "(title LIKE :q OR description LIKE :q)";
            $params['q'] = '%' . $q . '%';
        }
        
        if ($category !== '') {
            $conditions[] = "category = :category";
            $params['category'] = $category;
        }
        
        if ($era !== '') {
            $conditions[] = "era = :era";
            $params['era'] = $era;
        }
        
        if ($price !== '') {
            if ($price === '0-1000') {
                $conditions[] = "current_bid < 1000";
            } elseif ($price === '1000-5000') {
                $conditions[] = "current_bid >= 1000 AND current_bid <= 5000";
            } elseif ($price === '5000-10000') {
                $conditions[] = "current_bid >= 5000 AND current_bid <= 10000";
            } elseif ($price === '10000+') {
                $conditions[] = "current_bid > 10000";
            }
        }
        
        $whereSql = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $stmt = $pdo->prepare("SELECT * FROM auctions $whereSql ORDER BY ends_at ASC");
        $stmt->execute($params);
        $items = $stmt->fetchAll();
        
        require_once BASE_PATH . '/views/marketplace.php';
    }

    public function auction() {
        if (empty($_GET['id'])) {
            header("Location: " . BASE_URL . "marketplace");
            exit;
        }
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM auctions WHERE id = :id");
        $stmt->execute(['id' => $_GET['id']]);
        $auction = $stmt->fetch();
        
        if (!$auction) {
            header("Location: " . BASE_URL . "marketplace");
            exit;
        }

        $bStmt = $pdo->prepare("SELECT b.*, u.username FROM bids b JOIN users u ON b.user_id = u.id WHERE b.auction_id = :id ORDER BY b.amount DESC LIMIT 5");
        $bStmt->execute(['id' => $_GET['id']]);
        $bids = $bStmt->fetchAll();

        require_once BASE_PATH . '/views/auction.php';
    }

    public function dashboard() {
        if (empty($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit;
        }
        
        if (in_array($_SESSION['role'] ?? '', ['admin', 'curator'])) {
            header("Location: " . BASE_URL . "admin");
            exit;
        }
        
        $pdo = Database::getConnection();
        $uStmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $uStmt->execute(['id' => $_SESSION['user_id']]);
        $user = $uStmt->fetch();

        // Fetch total active bids count for stats
        $cStmt = $pdo->prepare("SELECT COUNT(DISTINCT auction_id) FROM bids WHERE user_id = :id");
        $cStmt->execute(['id' => $_SESSION['user_id']]);
        $activeBidsCount = $cStmt->fetchColumn();

        // Fetch recent active bids for user display

        $bStmt = $pdo->prepare("SELECT a.*, MAX(b.amount) as my_bid FROM bids b JOIN auctions a ON b.auction_id = a.id WHERE b.user_id = :id GROUP BY a.id ORDER BY MAX(b.created_at) DESC LIMIT 2");
        $bStmt->execute(['id' => $_SESSION['user_id']]);
        $activeEngagements = $bStmt->fetchAll();

        require_once BASE_PATH . '/views/dashboard.php';
    }

    public function myBids() {
        if (empty($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit;
        }
        
        if (in_array($_SESSION['role'] ?? '', ['admin', 'curator'])) {
            header("Location: " . BASE_URL . "admin");
            exit;
        }
        
        $pdo = Database::getConnection();
        $uStmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $uStmt->execute(['id' => $_SESSION['user_id']]);
        $user = $uStmt->fetch();
        $cStmt = $pdo->prepare("SELECT COUNT(DISTINCT auction_id) FROM bids WHERE user_id = :id");
        $cStmt->execute(['id' => $_SESSION['user_id']]);
        $activeBidsCount = $cStmt->fetchColumn();

        $bStmt = $pdo->prepare("SELECT a.*, MAX(b.amount) as my_bid FROM bids b JOIN auctions a ON b.auction_id = a.id WHERE b.user_id = :id GROUP BY a.id ORDER BY MAX(b.created_at) DESC");
        $bStmt->execute(['id' => $_SESSION['user_id']]);
        $activeEngagements = $bStmt->fetchAll();

        require_once BASE_PATH . '/views/my_bids.php';
    }
    public function staticPage() {
        $slug = $_GET['id'] ?? '';
        
        $pages = [
            'learn-more' => [
                'title' => 'Learn More',
                'content' => 'At Provenance Hub, we do more than auction. We preserve legacies. Every item is meticulously vetted by our historical scholars to ensure its story is told with the dignity it deserves. Learn more about our rigorous valuation process, the history of our institution established in 1924, and our commitment to authenticity.'
            ],
            'legal' => [
                'title' => 'Legal Information',
                'content' => 'All transactions conducted on Provenance Hub are legally binding. By participating in any auction, bidders agree to our comprehensive Terms of Service. Provenance Hub operates under strict regulatory compliance and ensures that all antique items conform to international heritage trading laws. We reserve the right to verify the identity of any participant and refuse service to any party suspected of fraudulent activity.'
            ],
            'privacy-policy' => [
                'title' => 'Privacy Policy',
                'content' => 'Your privacy is paramount to us. Provenance Hub collects only the information strictly necessary to facilitate secure auctions, process payments, and provide personalized curatorial services. We never sell your personal data to third parties. All personal and financial information is encrypted using state-of-the-art security protocols.'
            ],
            'cookies' => [
                'title' => 'Cookie Policy',
                'content' => 'Provenance Hub utilizes essential cookies to ensure the smooth operation of our online auction platform. These cookies manage active sessions, preserve your login state, and ensure real-time bid synchronization. We also employ anonymized analytics cookies to understand how our patrons interact with our collections, allowing us to continuously improve the archival and browsing experience.'
            ],
            'consign' => [
                'title' => 'Consign an Item',
                'content' => 'Considering consigning a treasured artifact? Provenance Hub offers a premier consignment service for distinguished collectors. Our expert curators will evaluate your piece, provide a fair market assessment, and present it to our global network of discerning buyers. From initial appraisal to final sale, we handle every detail with the utmost care and discretion. Contact our acquisitions team to begin the consignment process.'
            ],
            'valuation' => [
                'title' => 'Valuation Services',
                'content' => 'Our world-renowned panel of scholars, historians, and certified appraisers provides comprehensive valuation services for antiques, fine art, and rare collectibles. Whether for insurance purposes, estate planning, or sale preparation, Provenance Hub delivers meticulous, well-documented valuations you can trust. Each assessment includes provenance research, condition reporting, and current market analysis.'
            ]
        ];

        if (!array_key_exists($slug, $pages)) {
            header("HTTP/1.0 404 Not Found");
            echo "Page not found.";
            exit;
        }

        $pageData = $pages[$slug];
        require_once BASE_PATH . '/views/static_page.php';
    }

    public function timeline() {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM auctions WHERE status = 'active' ORDER BY ends_at ASC");
        $stmt->execute();
        $items = $stmt->fetchAll();
        require_once BASE_PATH . '/views/timeline.php';
    }
}
