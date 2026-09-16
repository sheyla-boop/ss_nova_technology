<?php

declare(strict_types=1);

$envFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
$environment = [];

if (is_readable($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $environment[trim($key)] = trim($value, " \t\n\r\0\x0B\"");
    }
}

return [
    'app' => [
        'env' => $environment['APP_ENV'] ?? 'development',
        'debug' => filter_var($environment['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOL),
        'url' => rtrim($environment['APP_URL'] ?? '', '/'),
    ],
    'database' => [
        'host' => $environment['DB_HOST'] ?? '127.0.0.1',
        'port' => $environment['DB_PORT'] ?? '3306',
        'name' => $environment['DB_NAME'] ?? 'ss_nova_technology',
        'user' => $environment['DB_USER'] ?? 'root',
        'pass' => $environment['DB_PASS'] ?? '',
        'charset' => $environment['DB_CHARSET'] ?? 'utf8mb4',
    ],
];
