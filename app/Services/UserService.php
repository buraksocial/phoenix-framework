<?php
namespace App\Services;

use Core\Database\Connection as DBConnection;
use Core\Auth;

class UserService
{
    protected $db;

    public function __construct(DBConnection $dbConnection)
    {
        $this->db = $dbConnection->connection();
    }

    public function authenticate(string $email, string $password): ?object
    {
        $user = $this->db->first("SELECT * FROM users WHERE email = ?", [$email]);

        if ($user && password_verify($password, $user->password)) {
            Auth::login($user);
            return $user;
        }

        return null;
    }

    public function createUser(string $name, string $email, string $password): object
    {
        // E-posta adresinin zaten kullanılıp kullanılmadığını kontrol et
        $existingUser = $this->db->first("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existingUser) {
            throw new \Exception("Bu e-posta adresi zaten kullanımda.");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userId = $this->db->insert('users', [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return (object)['id' => $userId, 'name' => $name, 'email' => $email];
    }

    public function findUserById(int $id): ?object
    {
        return $this->db->first("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public function findUserByEmail(string $email): ?object
    {
        return $this->db->first("SELECT * FROM users WHERE email = ?", [$email]);
    }
}
