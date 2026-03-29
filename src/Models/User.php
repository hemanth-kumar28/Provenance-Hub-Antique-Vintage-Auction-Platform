<?php
namespace App\Models;

use App\Core\Database;

/**
 * Representation of a User in the system.
 */
class User extends Database {
    private int $id;
    private string $username;
    private string $email;
    private string $role;

    public function __construct(int $id, string $username, string $email, string $role) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
    }

    public function getId(): int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
    public function getRole(): string { return $this->role; }

    public static function findById(int $id): ?self {
        $stmt = self::getConnection()->prepare("SELECT id, username, email, role FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        if ($data) {
            return new self($data['id'], $data['username'], $data['email'], $data['role']);
        }
        return null;
    }
}
