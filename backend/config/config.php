<?php

declare(strict_types=1);

function db_connect(): PDO
{
    load_project_env();

    $host = env_with_fallback(["DB_HOST"], "127.0.0.1");
    $port = env_with_fallback(["DB_PORT"], "3306");
    $database = require_env_with_fallback(["DB_DATABASE", "MYSQL_DATABASE"]);
    $username = require_env_with_fallback(["DB_USERNAME", "MYSQL_USER"]);
    $password = require_env_with_fallback(["DB_PASSWORD", "MYSQL_PASSWORD"]);

    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

    return new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

function require_env_with_fallback(array $keys): string
{
    foreach ($keys as $key) {
        $value = getenv($key);
        if ($value !== false && $value !== "") {
            return $value;
        }
    }

    throw new RuntimeException(
        "Variavel de ambiente obrigatoria ausente: " . implode(" ou ", $keys)
    );
}

function env_with_fallback(array $keys, string $default): string
{
    foreach ($keys as $key) {
        $value = getenv($key);
        if ($value !== false && $value !== "") {
            return $value;
        }
    }

    return $default;
}

function load_project_env(): void
{
    static $loaded = false;
    if ($loaded) {
        return;
    }

    $rootEnvPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . ".env";
    if (!is_file($rootEnvPath)) {
        $loaded = true;
        return;
    }

    $lines = file($rootEnvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!is_array($lines)) {
        $loaded = true;
        return;
    }

    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === "" || str_starts_with($trimmed, "#")) {
            continue;
        }

        $pair = explode("=", $trimmed, 2);
        if (count($pair) !== 2) {
            continue;
        }

        $key = trim($pair[0]);
        $value = trim($pair[1]);
        if ($key === "") {
            continue;
        }

        if (getenv($key) === false || getenv($key) === "") {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }

    $loaded = true;
}
