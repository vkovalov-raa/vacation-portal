<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\JsonResponse;

class Role
{

    /**
     * @param string $role
     * @param mixed $next
     * @return callable
     */
    public static function only(string $role, mixed $next): callable
    {
        if (is_array($next) && is_string($next[0])) {
            $next[0] = container($next[0]);
        }

        if (!\is_callable($next)) {
            throw new \InvalidArgumentException('$next must be callable');
        }

        return function (...$args) use ($role, $next) {
            $payload = $GLOBALS['auth_user'] ?? null;

            if (!$payload || ($payload['role'] ?? null) !== $role) {
                return new JsonResponse(['message' => 'Forbidden'], 403);
            }
            return $next(...$args);
        };
    }
}