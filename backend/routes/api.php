<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\JwtAuth;
use App\Http\Middleware\Role;
use Symfony\Component\HttpFoundation\JsonResponse;

return function (FastRoute\RouteCollector $r) {

    // public
    $r->addRoute('POST', '/api/auth/login', [AuthController::class, 'login']);

    // protected
    $r->addGroup('/api', function (FastRoute\RouteCollector $r) {

        $r->addRoute('GET', '/me', [AuthController::class, 'me']);

        $r->addRoute(
            'GET',
            '/manager/users',
            Role::only('manager', fn () => new JsonResponse([]))
        );

    });
};

