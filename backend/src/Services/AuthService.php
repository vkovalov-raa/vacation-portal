<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;

class AuthService
{
    public function __construct(private PDO $db) {}

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

    public function decode(string $jwt): ?array
    {
        try {
            return (array) JWT::decode($jwt, new Key($_ENV['JWT_SECRET'], 'HS256'));
        } catch (\Throwable) {
            return null;
        }
    }
}