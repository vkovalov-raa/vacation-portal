<?php

require __DIR__.'/../vendor/autoload.php';

Dotenv\Dotenv::createImmutable(__DIR__.'/..', '.env')->load();

$pdo = new \PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec(file_get_contents(__DIR__.'/schema.sql'));
$GLOBALS['pdo_test'] = $pdo;