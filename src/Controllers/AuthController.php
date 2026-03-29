<?php
namespace App\Controllers;

use App\Core\Database;
use App\Traits\AuditLogging;
use Exception;

class AuthController
{
    use AuditLogging;

    public function showLogin()
    {
        if (!empty($_SESSION['user_id'])) {
            if (in_array($_SESSION['role'] ?? '', ['admin', 'curator'])) {
                header("Location: " . BASE_URL . "admin");
            } else {
                header("Location: " . BASE_URL . "dashboard");
            }
            exit;
        }
        require_once BASE_PATH . '/views/auth/login.php';
    }

    public function showRegister()
    {
        if (!empty($_SESSION['user_id'])) {
            if (in_array($_SESSION['role'] ?? '', ['admin', 'curator'])) {
                header("Location: " . BASE_URL . "admin");
            } else {
                header("Location: " . BASE_URL . "dashboard");
            }
            exit;
        }
        require_once BASE_PATH . '/views/auth/register.php';
    }

    public function register()
    {
        if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die("CSRF Token Validation Failed.");
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'collector';

        if (!$username || !$email || !$password) {
            $error = "All fields are required.";
            require_once BASE_PATH . '/views/auth/register.php';
            return;
        }

        if (strlen($password) < 6 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[!@#\$%\^&\*]/', $password)) {
            $error = "Password does not meet security requirements.";
            require_once BASE_PATH . '/views/auth/register.php';
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $pdo = Database::getConnection();

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (:u, :e, :p, :r)");
            $stmt->execute([
                'u' => $username,
                'e' => $email,
                'p' => $hash,
                'r' => $role
            ]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['role'] = $role;
            $_SESSION['username'] = $username;

            $this->logAction('User Registered', 'User', $_SESSION['user_id'], "New collector '$username' established profile.");

            if (in_array($_SESSION['role'], ['admin', 'curator'])) {
                header("Location: " . BASE_URL . "admin");
            } else {
                header("Location: " . BASE_URL . "dashboard");
            }
            exit;
        }
        catch (Exception $e) {
            $error = "Registration failed. Username or email may already exist.";
            require_once BASE_PATH . '/views/auth/register.php';
        }
    }

    public function login()
    {
        if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die("CSRF Token Validation Failed.");
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :e LIMIT 1");
        $stmt->execute(['e' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            $this->logAction('User Login', 'User', $user['id'], "Collector '{$user['username']}' authenticated successfully.");

            if (in_array($_SESSION['role'], ['admin', 'curator'])) {
                header("Location: " . BASE_URL . "admin");
            } else {
                header("Location: " . BASE_URL . "dashboard");
            }
            exit;
        }
        else {
            $error = "Invalid credentials.";
            require_once BASE_PATH . '/views/auth/login.php';
        }
    }

    public function logout()
    {
        if (!empty($_SESSION['user_id'])) {
            $this->logAction('User Logout', 'User', $_SESSION['user_id'], "Collector '{$_SESSION['username']}' terminated session.");
        }
        session_destroy();
        header("Location: " . rtrim(BASE_URL, '/'));
        exit;
    }

    public function showForgot()
    {
        require_once BASE_PATH . '/views/auth/forgot.php';
    }

    public function forgot()
    {
        if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die("CSRF Token Validation Failed.");
        }
        $email = trim($_POST['email'] ?? '');

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :e LIMIT 1");
        $stmt->execute(['e' => $email]);

        if ($stmt->fetch()) {
            $code = sprintf("%06d", mt_rand(1, 999999));
            $update = $pdo->prepare("UPDATE users SET reset_code = :c, reset_expires = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = :e");
            $update->execute(['c' => $code, 'e' => $email]);

            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = //A WORKING GMAIL ACCOUNT WITH 2FA ENABLED CAN BE USED
                $mail->Password = //GENERATED PASSWORD FOR THAT ACCOUNT TO USE SMTP
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom($email->Username, 'Provenance Hub');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Code';
                $mail->Body = "Your verification code is: <b>$code</b>. It expires in 15 minutes. If you did not request a password reset, please ignore this email.";

                $mail->send();
            }
            catch (Exception $e) {
                error_log("PHPMailer Error: {$mail->ErrorInfo}");
            }
        }

        $this->logAction('Password Reset Request', 'User', null, "Password reset requested for email: $email");
        header("Location: " . BASE_URL . "verify-code?email=" . urlencode($email));
        exit;
    }

    public function showVerify()
    {
        require_once BASE_PATH . '/views/auth/verify_code.php';
    }

    public function verify()
    {
        if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die("CSRF Token Validation Failed.");
        }
        $email = trim($_POST['email'] ?? '');
        $code = trim($_POST['code'] ?? '');

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :e AND reset_code = :c AND reset_expires > NOW() LIMIT 1");
        $stmt->execute(['e' => $email, 'c' => $code]);
        if ($stmt->fetch()) {
            $_SESSION['reset_email'] = $email;
            header("Location: " . BASE_URL . "reset-password");
            exit;
        }
        else {
            $error = "Invalid or expired verification code.";
            require_once BASE_PATH . '/views/auth/verify_code.php';
        }
    }

    public function showReset()
    {
        if (empty($_SESSION['reset_email'])) {
            header("Location: " . BASE_URL . "forgot-password");
            exit;
        }
        require_once BASE_PATH . '/views/auth/reset_password.php';
    }

    public function reset()
    {
        if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die("CSRF Token Validation Failed.");
        }
        if (empty($_SESSION['reset_email'])) {
            header("Location: " . BASE_URL . "forgot-password");
            exit;
        }

        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        if ($password !== $confirm) {
            $error = "Passwords do not match.";
            require_once BASE_PATH . '/views/auth/reset_password.php';
            return;
        }
        if (strlen($password) < 6 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[!@#\$%\^&\*]/', $password)) {
            $error = "Password does not meet security requirements.";
            require_once BASE_PATH . '/views/auth/reset_password.php';
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE users SET password_hash = :p, reset_code = NULL, reset_expires = NULL WHERE email = :e");
        $stmt->execute(['p' => $hash, 'e' => $_SESSION['reset_email']]);

        unset($_SESSION['reset_email']);

        header("Location: " . BASE_URL . "login?reset_success=1");
        exit;
    }
}
