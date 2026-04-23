<?php

declare(strict_types=1);

function db_connect(): PDO
{
    $host = getenv("DB_HOST") ?: "db";
    $port = getenv("DB_PORT") ?: "3306";
    $database = require_env("DB_DATABASE");
    $username = require_env("DB_USERNAME");
    $password = require_env("DB_PASSWORD");

    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

    return new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

function require_env(string $key): string
{
    $value = getenv($key);
    if ($value === false || $value === "") {
        throw new RuntimeException("Variavel de ambiente obrigatoria ausente: {$key}");
    }

    return $value;
}
