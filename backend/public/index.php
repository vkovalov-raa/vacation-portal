<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FastRoute\Dispatcher;
use DI\ContainerBuilder;

require __DIR__.'/../vendor/autoload.php';

Dotenv\Dotenv::createImmutable(__DIR__.'/..')->safeLoad();

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__.'/../config/di.php');

$container = $builder->build();
$GLOBALS['container'] = $container;

function container(string $id) {
    return $GLOBALS['container']->get($id);
}

$dispatcher = FastRoute\simpleDispatcher(require __DIR__.'/../routes/api.php');
$request    = Request::createFromGlobals();

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

$response = match ($routeInfo[0]) {
    Dispatcher::NOT_FOUND          => new JsonResponse(['message'=>'Not found'], 404),
    Dispatcher::METHOD_NOT_ALLOWED => new JsonResponse(['message'=>'Method not allowed'], 405),

    Dispatcher::FOUND => (function () use ($routeInfo, $request, $container) {
        $handler = $routeInfo[1];

        if (is_array($handler) && is_string($handler[0])) {
            $handler[0] = $container->get($handler[0]);
        }
        return $handler($request, ...array_values($routeInfo[2]));
    })(),

    default => new JsonResponse(['message'=>'Unexpected'], 500),
};

$response->send();
