<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

function checkRequirements() {
    if (version_compare(PHP_VERSION, '7.2.0', '<')) {
        throw new \RuntimeException('You need PHP 7.2 to run this application');
    }
}

checkRequirements();
