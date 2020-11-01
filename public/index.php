<?php
declare(strict_types=1);

use Common\Web\ControllerResolver;
use DevPro\Infrastructure\DevelopmentServiceContainer;

require __DIR__ . '/../vendor/autoload.php';

$container = new DevelopmentServiceContainer(__DIR__ . '/../var');
$container->boot();

ControllerResolver::resolve($_SERVER, $_GET, $container->controllers())();
