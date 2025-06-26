<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    public static function only(string $role, callable $next): callable
    {
        return function (...$args) use ($role, $next) {
            $auth = container(AuthService::class);

            if (($auth->user()['role'] ?? null) !== $role) {
                return new Response('403 Forbidden', 403);
            }
            return $next(...$args);
        };
    }
}