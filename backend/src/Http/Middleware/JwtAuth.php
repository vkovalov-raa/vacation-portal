<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;

class JwtAuth
{
    public static function wrap(callable $next): callable
    {
        return function (...$args) use ($next) {
            $headers = apache_request_headers() ?: [];
            $auth    = $headers['Authorization'] ?? $headers['authorization'] ?? '';

            if (!preg_match('#^Bearer (.+)$#', $auth, $m)) {
                return new JsonResponse(['message' => 'Unauthorized'], 401);
            }

            $payload = container(AuthService::class)->decode($m[1]);
            if (!$payload) {
                return new JsonResponse(['message' => 'Invalid token'], 401);
            }

            $GLOBALS['auth_user'] = $payload;
            return $next(...$args);
        };
    }
}
