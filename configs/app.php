<?php

declare(strict_types=1);

use JR\ChefsDiary\Enums\AppEnvironmentEnum;

$boolean = function (mixed $value) {
    if (in_array($value, ['true', 1, '1', true, 'yes'], true)) {
        return true;
    }

    return false;
};
$appEnv = $_ENV['APP_ENV'] ?? AppEnvironmentEnum::Production->value;
$appSnakeName = strtolower(str_replace(' ', '_', $_ENV['APP_NAME']));

return [
    'app_key' => $_ENV['APP_KEY'] ?? '',
    'app_name' => $_ENV['APP_NAME'],
    'app_version' => $_ENV['APP_VERSION'] ?? '1.0',
    'app_url' => $_ENV['APP_URL'],
    'app_environment' => $appEnv,
    'display_error_details' => $boolean($_ENV['APP_DEBUG'] ?? 0),
    'log_errors' => true,
    'log_error_details' => true,
    'doctrine' => [
        'dev_mode' => AppEnvironmentEnum::isDevelopment($appEnv),
        'cache_dir' => STORAGE_PATH . '/cache/doctrine',
        'entity_dir' => [APP_PATH . '/Entity'],
        'connection' => [
            'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? 3306,
            'dbname' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
        ],
    ],
    'token' => [
        'exp_access' => time() + $_ENV['TOKEN_EXP_ACCESS'] ?? 0,
        'exp_refresh' => time() + $_ENV['TOKEN_EXP_REFRESH'] ?? 0,
        'algorithm' => 'HS256',
        'key_access' => $_ENV['TOKEN_KEY_ACCESS'],
        'key_refresh' => $_ENV['TOKEN_KEY_REFRESH']
    ],
    'auth_cookie' => [
        'name' => $appSnakeName . '_refreshToken',
        'secure' => $boolean($_ENV['AUTH_COOKIE_SECURE'] ?? true),
        'http_only' => $boolean($_ENV['AUTH_COOKIE_HTTP_ONLY'] ?? true),
        'same_site' => $_ENV['AUTH_COOKIE_SAME_SITE'] ?? 'lax',
        'expires' => time() + $_ENV['TOKEN_EXP_REFRESH'] ?? 0,
        'path' => $_ENV['AUTH_COOKIE_PATH'] ?? ''
    ],
    'session' => [
        'token_session_name' => $appSnakeName . '_token_session',
        'name' => $appSnakeName . '_session',
        'flash_name' => $appSnakeName . '_flash',
        'secure' => $boolean($_ENV['SESSION_SECURE'] ?? true),
        'httponly' => $boolean($_ENV['SESSION_HTTP_ONLY'] ?? true),
        'samesite' => $_ENV['SESSION_SAME_SITE'] ?? 'lax',
    ],
];