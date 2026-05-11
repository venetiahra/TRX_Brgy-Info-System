<?php
require_once __DIR__ . '/../config/app.php';

class User
{
    private $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function countUsers(): int
    {
        return (int) $this->conn->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    public function findUserByUsername(string $username): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => trim($username)]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function createDefaultAdmin(string $fullname, string $username, string $password, string $role = 'admin'): bool
    {
        $stmt = $this->conn->prepare('INSERT INTO users (username, password, fullname, role) VALUES (:username, :password, :fullname, :role)');
        return $stmt->execute([
            'username' => trim($username),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'fullname' => trim($fullname),
            'role' => trim($role),
        ]);
    }

    public function login(string $username, string $password): bool
    {
        $user = $this->findUserByUsername($username);
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'fullname' => $user['fullname'],
            'role' => $user['role'],
        ];
        return true;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
    }
}
?>
