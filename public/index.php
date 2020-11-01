<?php
declare(strict_types=1);

use DevPro\Infrastructure\DevelopmentServiceContainer;

require __DIR__ . '/../vendor/autoload.php';

$container = new DevelopmentServiceContainer(__DIR__ . '/../');
$container->boot();

$container->createUser()->create('Menno Backer');

header('Content-Type: text/plain');
echo 'Hello, world!';
