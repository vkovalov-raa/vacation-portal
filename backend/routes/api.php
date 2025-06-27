<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VacationController;
use App\Http\Middleware\Role;
use App\Http\Middleware\JwtAuth;

return function (FastRoute\RouteCollector $r) {

    // public
    $r->addRoute('POST', '/api/auth/login', [AuthController::class, 'login']);

    // protected
    $r->addGroup('/api', function (FastRoute\RouteCollector $r) {

        $r->addRoute('GET', '/me', JwtAuth::wrap([AuthController::class, 'me']));

        $r->addRoute('GET',  '/vacations', JwtAuth::wrap([VacationController::class, 'index']));
        $r->addRoute('POST', '/vacations', JwtAuth::wrap([VacationController::class, 'store']));

        $r->addRoute('GET',   '/manager/vacations',
            JwtAuth::wrap(Role::only('manager', fn (...$a) => (container(VacationController::class))->all(...$a)))
        );
        $r->addRoute('PATCH', '/manager/vacations/{id:\d+}',
            JwtAuth::wrap(Role::only('manager', fn (...$a) => (container(VacationController::class))->updateStatus(...$a)))
        );
        $r->addRoute('GET',    '/manager/users',
            JwtAuth::wrap(Role::only('manager', [UserController::class,'index'])));

        $r->addRoute('POST',   '/manager/users',
            JwtAuth::wrap(Role::only('manager', [UserController::class,'store'])));

        $r->addRoute('PATCH',  '/manager/users/{id:\d+}',
            JwtAuth::wrap(Role::only('manager', [UserController::class,'update'])));

        $r->addRoute('DELETE', '/manager/users/{id:\d+}',
            JwtAuth::wrap(Role::only('manager', [UserController::class,'destroy'])));

    });
};

