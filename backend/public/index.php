<?php

declare(strict_types=1);

session_start();

if (isset($_SESSION["user"])) {
    header("Location: /dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once __DIR__ . "/../config/config.php";

    $username = trim((string) ($_POST["username"] ?? ""));
    $password = (string) ($_POST["password"] ?? "");

    if ($username === "" || $password === "") {
        $error = "Preencha usuario e senha.";
    } else {
        try {
            $pdo = db_connect();
            $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1");
            $stmt->execute(["username" => $username]);
            $user = $stmt->fetch();

            if ($user && hash("sha256", $password) === $user["password_hash"]) {
                $_SESSION["user"] = [
                    "id" => (int) $user["id"],
                    "username" => $user["username"],
                ];
                header("Location: /dashboard.php");
                exit;
            }

            $error = "Usuario ou senha invalidos.";
        } catch (Throwable $e) {
            $error = "Erro ao conectar no banco. Verifique as variaveis e migrations.";
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Base do Prumo</title>
  <style>
    body {
      margin: 0;
      min-height: 100vh;
      display: grid;
      place-items: center;
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #0f172a, #1d4ed8);
    }
    .card {
      width: min(92vw, 420px);
      background: #fff;
      border-radius: 14px;
      padding: 24px;
      box-shadow: 0 16px 30px rgba(0, 0, 0, 0.22);
    }
    h1 { margin-top: 0; margin-bottom: 6px; }
    p { margin-top: 0; color: #475569; }
    label { display: block; margin-top: 12px; font-weight: 600; }
    input {
      width: 100%;
      box-sizing: border-box;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #cbd5e1;
      margin-top: 6px;
    }
    button {
      width: 100%;
      margin-top: 16px;
      padding: 10px;
      border: 0;
      border-radius: 8px;
      background: #2563eb;
      color: #fff;
      font-weight: 700;
      cursor: pointer;
    }
    .error {
      margin-top: 12px;
      padding: 10px;
      border-radius: 8px;
      color: #7f1d1d;
      background: #fee2e2;
    }
    .hint {
      margin-top: 12px;
      font-size: 0.9rem;
      color: #475569;
    }
  </style>
</head>
<body>
  <main class="card">
    <h1>Entrar</h1>
    <p>Login conectado ao MySQL.</p>
    <form method="post" action="/">
      <label for="username">Usuario</label>
      <input id="username" name="username" required>

      <label for="password">Senha</label>
      <input id="password" name="password" type="password" required>

      <button type="submit">Acessar</button>
    </form>
    <?php if ($error !== ""): ?>
      <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
    <?php endif; ?>
    <div class="hint">Use os usuarios do arquivo SQL de seed para testar.</div>
  </main>
</body>
</html>
