<?php

declare(strict_types=1);

session_start();
require_once __DIR__ . "/../config/config.php";

const MAX_LOGIN_ATTEMPTS = 5 # teste para quebra projeto
const LOCKOUT_SECONDS = 120;

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$method = $_SERVER["REQUEST_METHOD"] ?? "GET";
$pathInfo = (string) ($_SERVER["PATH_INFO"] ?? "/api");
$normalizedPath = preg_replace("#^/api(?:\\.php)?/?#", "", $pathInfo);
$route = trim((string) $normalizedPath, "/");

if ($route === "" && $method === "GET") {
    json_response(200, [
        "ok" => true,
        "message" => "API online",
    ]);
}

if ($route === "csrf" && $method === "GET") {
    json_response(200, [
        "ok" => true,
        "csrf_token" => (string) $_SESSION["csrf_token"],
    ]);
}

if ($route === "auth/me" && $method === "GET") {
    if (!isset($_SESSION["user"])) {
        json_response(401, ["ok" => false, "message" => "Nao autenticado."]);
    }

    json_response(200, [
        "ok" => true,
        "user" => $_SESSION["user"],
    ]);
}

if ($route === "auth/login" && $method === "POST") {
    $payload = read_json_input();
    $csrfToken = (string) ($payload["csrf_token"] ?? "");
    $username = trim((string) ($payload["username"] ?? ""));
    $password = (string) ($payload["password"] ?? "");

    $lockedUntil = (int) ($_SESSION["locked_until"] ?? 0);
    if ($lockedUntil > time()) {
        $remaining = $lockedUntil - time();
        json_response(429, [
            "ok" => false,
            "message" => "Muitas tentativas. Tente novamente em {$remaining}s.",
        ]);
    }

    if (!hash_equals((string) $_SESSION["csrf_token"], $csrfToken)) {
        json_response(403, ["ok" => false, "message" => "Token CSRF invalido."]);
    }

    if ($username === "" || $password === "") {
        json_response(422, ["ok" => false, "message" => "Preencha usuario e senha."]);
    }

    if (strlen($username) < 3 || strlen($username) > 100 || !preg_match("/^[a-zA-Z0-9._-]+$/", $username)) {
        json_response(422, ["ok" => false, "message" => "Usuario invalido."]);
    }

    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(["username" => $username]);
        $user = $stmt->fetch();

        if (!$user || !verify_user_password($password, (string) $user["password_hash"])) {
            $attempts = (int) ($_SESSION["failed_login_attempts"] ?? 0) + 1;
            $_SESSION["failed_login_attempts"] = $attempts;
            if ($attempts >= MAX_LOGIN_ATTEMPTS) {
                $_SESSION["locked_until"] = time() + LOCKOUT_SECONDS;
                $_SESSION["failed_login_attempts"] = 0;
            }

            json_response(401, ["ok" => false, "message" => "Usuario ou senha invalidos."]);
        }

        session_regenerate_id(true);
        $_SESSION["user"] = [
            "id" => (int) $user["id"],
            "username" => (string) $user["username"],
        ];
        unset($_SESSION["failed_login_attempts"], $_SESSION["locked_until"]);

        json_response(200, [
            "ok" => true,
            "message" => "Login realizado com sucesso.",
            "user" => $_SESSION["user"],
        ]);
    } catch (Throwable $e) {
        error_log("[auth/login] " . $e->getMessage());
        $payload = ["ok" => false, "message" => "Erro interno ao autenticar."];
        if (is_debug_mode()) {
            $payload["debug"] = $e->getMessage();
        }

        json_response(500, $payload);
    }
}

if ($route === "auth/logout" && $method === "POST") {
    $payload = read_json_input();
    $csrfToken = (string) ($payload["csrf_token"] ?? "");
    if (!hash_equals((string) $_SESSION["csrf_token"], $csrfToken)) {
        json_response(403, ["ok" => false, "message" => "Token CSRF invalido."]);
    }

    session_unset();
    session_destroy();
    json_response(200, ["ok" => true, "message" => "Logout realizado."]);
}

if ($route === "dashboard/summary" && $method === "GET") {
    if (!isset($_SESSION["user"])) {
        json_response(401, ["ok" => false, "message" => "Nao autenticado."]);
    }

    try {
        $pdo = db_connect();
        $totalUsers = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stmt = $pdo->prepare(
            "SELECT username, created_at
             FROM users
             ORDER BY created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(":limit", 5, PDO::PARAM_INT);
        $stmt->execute();
        $recentUsers = $stmt->fetchAll();

        json_response(200, [
            "ok" => true,
            "summary" => [
                "database" => (getenv("DB_DATABASE") ?: (getenv("MYSQL_DATABASE") ?: "")),
                "totalUsers" => $totalUsers,
                "recentUsers" => $recentUsers,
            ],
        ]);
    } catch (Throwable $e) {
        error_log("[dashboard/summary] " . $e->getMessage());
        $payload = ["ok" => false, "message" => "Erro ao carregar dashboard."];
        if (is_debug_mode()) {
            $payload["debug"] = $e->getMessage();
        }

        json_response(500, $payload);
    }
}

json_response(404, ["ok" => false, "message" => "Rota nao encontrada."]);

function read_json_input(): array
{
    $body = file_get_contents("php://input");
    if (!is_string($body) || trim($body) === "") {
        return [];
    }

    $data = json_decode($body, true);
    return is_array($data) ? $data : [];
}

function verify_user_password(string $plain, string $storedHash): bool
{
    if (preg_match("/^\$2y\$/", $storedHash) === 1 || preg_match("/^\$argon2/", $storedHash) === 1) {
        return password_verify($plain, $storedHash);
    }

    return hash("sha256", $plain) === $storedHash;
}

function json_response(int $status, array $payload): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function is_debug_mode(): bool
{
    $appDebug = strtolower((string) getenv("APP_DEBUG"));
    $appEnv = strtolower((string) getenv("APP_ENV"));

    return $appDebug === "1"
        || $appDebug === "true"
        || $appEnv === "local"
        || $appEnv === "development";
}
