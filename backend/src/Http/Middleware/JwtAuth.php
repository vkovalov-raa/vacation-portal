<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;

class JwtAuth
{
    /**
     * @param mixed $next
     * @return callable
     */
    public static function wrap(mixed $next): callable
    {
        if (is_array($next) && is_string($next[0])) {
            $next[0] = container($next[0]);
        }

        if (!is_callable($next)) {
            throw new \InvalidArgumentException('$next must be callable');
        }

        return function (...$args) use ($next) {
            $headers = apache_request_headers() ?: [];
            $authHdr = $headers['Authorization'] ?? '';

            if (!preg_match('#^Bearer (.+)$#', $authHdr, $m)) {
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
