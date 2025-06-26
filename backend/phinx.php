<?php

return [
    'paths' => [
        'migrations' => 'db/migrations',
        'seeds'      => 'db/seeds',
    ],
    'environments' => [
        'default_environment' => 'development',
        'environments' => [
            'development' => [
                'adapter' => 'mysql',
                'host'    => $_ENV['DB_HOST'] ?? 'db',
                'name'    => $_ENV['DB_DATABASE'] ?? 'vacation',
                'user'    => $_ENV['DB_USERNAME'] ?? 'vacation',
                'pass'    => $_ENV['DB_PASSWORD'] ?? 'vacation',
                'port'    => 3306,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
        ]
    ],
];
