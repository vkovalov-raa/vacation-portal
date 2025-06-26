<?php
use function DI\autowire;
use App\Services\MailerService;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Psr\Container\ContainerInterface;

return [

    PDO::class => function () {
        return new PDO(
            sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4',
                $_ENV['DB_HOST'], $_ENV['DB_DATABASE']),
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    },


    Mailer::class => function () {
        return new Mailer(Transport::fromDsn($_ENV['MAILER_DSN']));
    },
    MailerService::class => autowire(),


    App\Services\AuthService::class      => autowire(),
    App\Services\UserService::class      => autowire(),
    App\Services\VacationService::class  => autowire(),

    App\Http\Controllers\Api\AuthController::class     => autowire(),
    App\Http\Controllers\Api\UserController::class     => autowire(),
    App\Http\Controllers\Api\VacationController::class => autowire(),
];
