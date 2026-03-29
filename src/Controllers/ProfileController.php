<?php
namespace App\Controllers;

use App\Core\Database;
use App\Traits\AuditLogging;

class ProfileController {
    use AuditLogging;
    public function index() {
        if (empty($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit;
        }
        
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        require_once BASE_PATH . '/views/profile/settings.php';
    }

    public function update() {
        if (empty($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit;
        }

        if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die("CSRF Token Validation Failed.");
        }

        $pdo = Database::getConnection();
        
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        if ($username && $email) {
            $stmt = $pdo->prepare("UPDATE users SET username = :u, email = :e WHERE id = :id");
            $stmt->execute(['u' => $username, 'e' => $email, 'id' => $_SESSION['user_id']]);
            $this->logAction('Profile Update', 'User', $_SESSION['user_id'], "Collector updated profile parameters. Alias: $username");
            $_SESSION['username'] = $username;
        }

        header("Location: " . BASE_URL . "profile?success=1");
        exit;
    }
}
