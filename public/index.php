<?php
session_start();
require 'conexao.php';

try {
    $checkAdmin = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    if ($checkAdmin == 0) {
        $senhaHash = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->query("INSERT INTO usuarios (usuario, senha, perfil) VALUES ('admin', '$senhaHash', 'admin')");
    }
} catch (PDOException $e) {}

$erro = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario_logado'] = $user['usuario'];
        $_SESSION['perfil'] = $user['perfil'];
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = "<div class='alert alert-danger mb-4 rounded-0' style='background-color: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid #ef4444;'>Usuário ou senha incorretos!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Asserti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #171717; 
            background-image: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop'); /* Fundo de prédios */
            background-size: cover; background-position: center;
            color: #e5e7eb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; min-height: 100vh; margin: 0; 
            display: flex; align-items: center; justify-content: center;
        }
        /* Sobreposição escura para a imagem de fundo */
        body::before { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(18, 21, 28, 0.85); z-index: -1; }
        
        /* Design Quadrado e Sólido */
        .flat-card { background: rgba(33, 33, 33, 0.95); border: 1px solid #444; border-radius: 0; padding: 50px 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); width: 100%; max-width: 450px; }
        .flat-input { background: transparent !important; border: 1px solid #555 !important; color: white !important; border-radius: 0 !important; padding: 12px 15px; }
        .flat-input:focus { border-color: #8b5cf6 !important; box-shadow: none !important; }
        .btn-login { background-color: #2a2a2a; border: 1px solid #444; color: white; transition: 0.3s; border-radius: 0; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .btn-login:hover { background-color: #8b5cf6; border-color: #8b5cf6; color: white; }
    </style>
</head>
<body>
    <div class="flat-card text-center">
        <h2 class="mb-1 text-white fw-bold">Login</h2>
        <p class="text-secondary mb-4" style="font-size: 0.9rem; font-style: italic;">Logue com sua conta preenchendo os campos abaixo!</p>
        <?= $erro ?>
        <form action="index.php" method="POST">
            <div class="mb-4"><input type="text" name="usuario" class="form-control flat-input" placeholder="Digite seu usuário" required></div>
            <div class="mb-5"><input type="password" name="senha" class="form-control flat-input" placeholder="Digite sua senha" required></div>
            <button type="submit" class="btn btn-login w-100 py-3">Entrar</button>
        </form>
        <div class="mt-4"><small class="text-secondary">Não possui um cadastro? <a href="#" style="color: #3b82f6; text-decoration: none;">crie um!</a></small></div>
    </div>
</body>
</html>