<?php

declare(strict_types=1);

use JR\ChefsDiary\Enums\AppEnvironmentEnum;

$appEnv = $_ENV['APP_ENV'] ?? AppEnvironmentEnum::Production->value;
$isDevelopment = AppEnvironmentEnum::isDevelopment($appEnv);

if ($isDevelopment) {
    header("Access-Control-Allow-Origin: " . $_ENV["ALLOWED_ORIGINS"]);
}