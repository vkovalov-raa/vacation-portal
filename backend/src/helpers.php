<?php

use App\Http\Controllers\Api\AuthController;
use App\Services\AuthService;
use App\Services\VacationService;
use Firebase\JWT\JWT;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

/**
 * Простейший DI-контейнер-singletons.
 * Вызывает себя так: container(PDO::class) или container(AuthController::class)
 */
function container(string $class)
{
    static $instances = [];

    if (isset($instances[$class])) {
        return $instances[$class];
    }

    return $instances[$class] = match ($class) {
        // --- Infrastructure
        PDO::class => new PDO(
            sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                $_ENV['DB_HOST'],
                $_ENV['DB_DATABASE']
            ),
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        ),

        CsrfTokenManager::class => new CsrfTokenManager(),

        // --- Services
        AuthService::class => new AuthService(container(PDO::class)),
        VacationService::class => new VacationService(container(PDO::class)),

        // --- Controllers
        AuthController::class => new AuthController(container(AuthService::class)),

        default => new $class()
    };
}