<?php

declare(strict_types=1);

$path = parse_url($_SERVER["REQUEST_URI"] ?? "/", PHP_URL_PATH);
if (!is_string($path)) {
    $path = "/";
}

if (str_starts_with($path, "/api/") || $path === "/api") {
    require __DIR__ . "/api.php";
    return true;
}

if ($path === "/health") {
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(["ok" => true], JSON_UNESCAPED_UNICODE);
    return true;
}

return false;
