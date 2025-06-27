<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;

class AuthService
{
    public function __construct(private PDO $db) {}

    /**
     * @param  int $id
     * @return array{id:int,name:string,email:string,role:string}|null
     */
    public function findUser(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id,name,email,role FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** @return array{token:string,user:array}|null */
    public function attempt(string $email, string $password): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return null;
        }

        $payload = [
            'sub'  => $user['id'],
            'role' => $user['role'],
            'iat'  => time(),
            'exp'  => time() + 60 * 60 * 8, // 8 hours
        ];

        $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

        return ['token' => $token, 'user' => [
            'id'   => $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
            'email'=> $user['email'],
        ]];
    }

    /**
     * @param  string $jwt
     * @return array|null
     */
    public function decode(string $jwt): ?array
    {
        try {
            return (array) JWT::decode($jwt, new Key($_ENV['JWT_SECRET'], 'HS256'));
        } catch (\Throwable) {
            return null;
        }
    }
}