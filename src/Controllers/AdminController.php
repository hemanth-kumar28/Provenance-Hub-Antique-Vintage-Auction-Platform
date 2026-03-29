<?php
namespace App\Controllers;

use App\Core\Database;
use App\Traits\AuditLogging;
use App\Traits\GDImageProcessor;

class AdminController {
    use AuditLogging, GDImageProcessor;

    private function checkAdmin() {
        if (empty($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'curator'])) {
            http_response_code(403);
            require_once BASE_PATH . '/views/partials/header.php';
            echo '<main class="max-w-7xl mx-auto px-6 py-24 text-center"><h1 class="text-4xl font-display mb-4 text-red-600">Access Denied</h1><p class="font-body text-lg text-outline">You require Administrative or Curator privileges.</p></main>';
            require_once BASE_PATH . '/views/partials/footer.php';
            exit;
        }
    }

    public function dashboard() {
        $this->checkAdmin();
        $pdo = Database::getConnection();
        
        $aStmt = $pdo->prepare("SELECT * FROM auctions ORDER BY created_at DESC");
        $aStmt->execute();
        $auctions = $aStmt->fetchAll();

        $uStmt = $pdo->prepare("SELECT * FROM users ORDER BY created_at DESC");
        $uStmt->execute();
        $users = $uStmt->fetchAll();
        
        require_once BASE_PATH . '/views/admin/dashboard.php';
    }

    public function create() {
        $this->checkAdmin();
        $auction = null;
        require_once BASE_PATH . '/views/admin/form.php';
    }

    public function store() {
        $this->checkAdmin();
        if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) die("CSRF Failed");

        $pdo = Database::getConnection();
        $image_url = 'images/furniture.jpg';
        
        $image_url_input = trim($_POST['image_url_input'] ?? '');
        if (!empty($image_url_input) && filter_var($image_url_input, FILTER_VALIDATE_URL)) {
            $image_url = filter_var($image_url_input, FILTER_SANITIZE_URL);
        } elseif (!empty($_FILES['image']['tmp_name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $filename = uniqid() . '.' . $ext;
                $dest = rtrim(UPLOAD_PATH, '/') . '/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $image_url = 'public/uploads/' . $filename;
                }
            }
        }

        $title = htmlspecialchars(strip_tags(trim($_POST['title'])));
        $lot_number = htmlspecialchars(strip_tags(trim($_POST['lot_number'])));
        $category = htmlspecialchars(strip_tags(trim($_POST['category'])));
        $era = htmlspecialchars(strip_tags(trim($_POST['era'])));
        $starting_price = filter_var($_POST['starting_price'], FILTER_VALIDATE_FLOAT);
        if ($starting_price === false || $starting_price < 0) $starting_price = 0;
        $ends_at = trim($_POST['ends_at']);
        $description = htmlspecialchars(strip_tags(trim($_POST['description'])));

        $stmt = $pdo->prepare("INSERT INTO auctions (title, lot_number, category, era, starting_price, current_bid, ends_at, description, image_url, status) VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?, 'active')");
        $stmt->execute([
            $title,
            $lot_number,
            $category,
            $era,
            $starting_price,
            $ends_at,
            $description,
            $image_url
        ]);
        
        $this->logAction("Created Auction", "Auction", $pdo->lastInsertId(), "Admin mapped new listing: " . trim($_POST['title']));
        header("Location: " . BASE_URL . "admin?success=created");
        exit;
    }

    public function edit() {
        $this->checkAdmin();
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM auctions WHERE id = ?");
        $stmt->execute([$_GET['id'] ?? 0]);
        $auction = $stmt->fetch();
        if (!$auction) die("Not found");
        require_once BASE_PATH . '/views/admin/form.php';
    }

    public function update() {
        $this->checkAdmin();
        if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) die("CSRF Failed");

        $pdo = Database::getConnection();
        $id = (int)$_POST['id'];
        $image_url = $_POST['existing_image'];
        
        $image_url_input = trim($_POST['image_url_input'] ?? '');
        if (!empty($image_url_input) && filter_var($image_url_input, FILTER_VALIDATE_URL)) {
            $image_url = filter_var($image_url_input, FILTER_SANITIZE_URL);
        } elseif (!empty($_FILES['image']['tmp_name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $filename = uniqid() . '.' . $ext;
                $dest = rtrim(UPLOAD_PATH, '/') . '/' . $filename;
                if ($this->processImage($_FILES['image']['tmp_name'], $dest, 800, 800)) {
                    $image_url = 'public/uploads/' . $filename;
                }
            }
        }

        $title = htmlspecialchars(strip_tags(trim($_POST['title'])));
        $lot_number = htmlspecialchars(strip_tags(trim($_POST['lot_number'])));
        $category = htmlspecialchars(strip_tags(trim($_POST['category'])));
        $era = htmlspecialchars(strip_tags(trim($_POST['era'])));
        $starting_price = filter_var($_POST['starting_price'], FILTER_VALIDATE_FLOAT);
        if ($starting_price === false || $starting_price < 0) $starting_price = 0;
        $ends_at = trim($_POST['ends_at']);
        $description = htmlspecialchars(strip_tags(trim($_POST['description'])));

        $stmt = $pdo->prepare("UPDATE auctions SET title=?, lot_number=?, category=?, era=?, starting_price=?, ends_at=?, description=?, image_url=? WHERE id=?");
        $stmt->execute([
            $title,
            $lot_number,
            $category,
            $era,
            $starting_price,
            $ends_at,
            $description,
            $image_url,
            $id
        ]);
        
        $this->logAction("Updated Auction", "Auction", $id, "Admin updated listing.");
        header("Location: " . BASE_URL . "admin?success=updated");
        exit;
    }

    public function delete() {
        $this->checkAdmin();
        if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) die("CSRF Failed");

        $pdo = Database::getConnection();
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM auctions WHERE id=?");
        $stmt->execute([$id]);
        
        $this->logAction("Deleted Auction", "Auction", $id, "Admin deleted listing.");
        header("Location: " . BASE_URL . "admin?success=deleted");
        exit;
    }
}
