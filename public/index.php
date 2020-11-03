<?php
declare(strict_types=1);

use DevPro\Infrastructure\DevelopmentServiceContainer;

require __DIR__ . '/../vendor/autoload.php';

$environment = $_COOKIE['environment'] ?? 'development';
$container = new DevelopmentServiceContainer(__DIR__ . '/../var', $environment);
$container->boot();

$container->webApplication()->run();
