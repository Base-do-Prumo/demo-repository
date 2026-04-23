<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION["user"])) {
    header("Location: /");
    exit;
}

$username = (string) $_SESSION["user"]["username"];
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel</title>
  <style>
    body {
      margin: 0;
      min-height: 100vh;
      display: grid;
      place-items: center;
      font-family: Arial, sans-serif;
      background: #f1f5f9;
    }
    .card {
      width: min(92vw, 560px);
      background: #fff;
      border-radius: 14px;
      padding: 24px;
      box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
      text-align: center;
    }
    a {
      display: inline-block;
      margin-top: 12px;
      color: #1d4ed8;
      text-decoration: none;
      font-weight: 700;
    }
  </style>
</head>
<body>
  <main class="card">
    <h1>Login realizado com sucesso</h1>
    <p>Usuário logado: <strong><?= htmlspecialchars($username, ENT_QUOTES, "UTF-8") ?></strong></p>
    <p>Gerencie o banco em <a href="/db/" target="_blank" rel="noopener noreferrer">phpMyAdmin</a>.</p>
    <a href="/logout.php">Sair</a>
  </main>
</body>
</html>
