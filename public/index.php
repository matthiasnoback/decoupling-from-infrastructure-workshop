<?php
declare(strict_types=1);

use DevPro\Infrastructure\DevelopmentServiceContainer;
use Symfony\Component\ErrorHandler\Debug;

require __DIR__ . '/../vendor/autoload.php';

$environment = $_COOKIE['environment'] ?? 'development';
$container = DevelopmentServiceContainer::createForEnvironment(__DIR__ . '/../var', $environment);

if ($environment === 'development') {
    Debug::enable();
}

$container->webApplication()->run();
