<?php
declare(strict_types=1);

try {
    $apiKey = getenv('ABSTRACT_API_API_KEY');
    if (!$apiKey) {
        throw new RuntimeException('Environment variable ABSTRACT_API_API_KEY has to be defined');
    }

    if ($apiKey !== ($_GET['api_key'] ?? '')) {
        throw new RuntimeException('Incorrect API key provided');
    }

    $responseFile = sprintf(
        '%04d-%02d-%02d-%s.json',
        $_GET['year'] ?? 0,
        $_GET['month'] ?? 0,
        $_GET['day'] ?? 0,
        $_GET['country'] ?? ''
    );

    if (!file_exists($responseFile)) {
        throw new RuntimeException('Could not find a suitable response file');
    }

    header('Content-Type: application/json');
    echo file_get_contents($responseFile);
} catch (Throwable $throwable) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    throw $throwable;
}
