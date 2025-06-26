<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VacationController;
use App\Http\Middleware\Role;
use Symfony\Component\HttpFoundation\JsonResponse;

return function (FastRoute\RouteCollector $r) {

    // public
    $r->addRoute('POST', '/api/auth/login', [AuthController::class, 'login']);

    // protected
    $r->addGroup('/api', function (FastRoute\RouteCollector $r) {

        $r->addRoute('GET', '/me', [AuthController::class, 'me']);

        /* employee */
        $r->addRoute('GET',  '/vacations', [VacationController::class, 'index']);
        $r->addRoute('POST', '/vacations', [VacationController::class, 'store']);

        /* manager */
        $r->addRoute('GET',   '/manager/vacations',
            Role::only('manager', fn (...$a) => (container(VacationController::class))->all(...$a))
        );
        $r->addRoute('PATCH', '/manager/vacations/{id:\d+}',
            Role::only('manager', fn (...$a) => (container(VacationController::class))->updateStatus(...$a))
        );

        $r->addRoute(
            'GET',
            '/manager/users',
            Role::only('manager', fn () => new JsonResponse([]))
        );

    });
};

