<?php
session_start();
require 'seguranca.php';
require 'conexao.php';

$mensagem = '';
$usuario_logado = $_SESSION['usuario_logado'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senha_atual = $_POST['senha_atual']; $nova_senha = $_POST['nova_senha']; $confirma_senha = $_POST['confirma_senha'];
    if ($nova_senha !== $confirma_senha) {
        $mensagem = "<div class='alert alert-warning mt-3 rounded-0' style='background: rgba(234, 179, 8, 0.1); color: #eab308; border: 1px solid #eab308;'>Senhas não coincidem.</div>";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario_logado]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($senha_atual, $user['senha'])) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE usuarios SET senha = ? WHERE usuario = ?")->execute([$nova_senha_hash, $usuario_logado]);
            $mensagem = "<div class='alert alert-success mt-3 rounded-0' style='background: rgba(16,185,129,0.1); color:#22c55e; border:1px solid #22c55e;'>Senha alterada!</div>";
        } else {
            $mensagem = "<div class='alert alert-danger mt-3 rounded-0' style='background: rgba(239,68,68,0.1); color:#ef4444; border:1px solid #ef4444;'>Senha atual incorreta.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Asserti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #171717 !important; color: #e5e7eb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; overflow-x: hidden; margin: 0; min-height: 100vh; }
        .sidebar { width: 250px; height: 100vh; background-color: #212121; border-right: 1px solid #333; position: fixed; display: flex; flex-direction: column; z-index: 1000;}
        .sidebar a { color: #888; text-decoration: none; display: block; padding: 12px 25px; font-weight: 500; transition: 0.2s; font-size: 0.95rem; border-radius: 0;}
        .sidebar a:hover, .sidebar a.active { color: #fff; background: #2a2a2a; border-left: 3px solid #3b82f6; }
        .btn-logout { color: #ef4444 !important; margin-top: auto; margin-bottom: 20px; }
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); background-color: #171717; }
        
        .flat-card { background-color: #212121; border: 1px solid #333; border-radius: 0; padding: 40px; max-width: 500px; margin: 0 auto;}
        .flat-input { background: transparent !important; border: 1px solid #555 !important; color: white !important; border-radius: 0 !important; padding: 12px 15px; }
        .flat-input:focus { border-color: #3b82f6 !important; box-shadow: none !important; }
        .btn-submit { background-color: #3b82f6; color: white; border: none; padding: 12px 25px; border-radius: 0; font-weight: bold; width: 100%; }
        hr { border-color: #333; margin: 25px 0;}
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="main-content">
        <div class="flat-card mt-5">
            <h4 class="fw-bold mb-1 text-center text-white">Meu Perfil</h4>
            <p style="color: #888; font-style: italic;" class="text-center mb-4">Atualize suas credenciais de acesso.</p>
            
            <div class="text-center mb-4 text-white">Usuário Logado: <strong style="color: #3b82f6;"><?= htmlspecialchars($usuario_logado) ?></strong></div>
            <?= $mensagem ?>

            <form action="perfil.php" method="POST">
                <div class="mb-4"><label style="color: #888;" class="form-label small">Senha Atual</label><input type="password" name="senha_atual" class="form-control flat-input" required></div>
                <hr>
                <div class="mb-3"><label style="color: #888;" class="form-label small">Nova Senha</label><input type="password" name="nova_senha" class="form-control flat-input" required minlength="4"></div>
                <div class="mb-4"><label style="color: #888;" class="form-label small">Confirme a Nova Senha</label><input type="password" name="confirma_senha" class="form-control flat-input" required minlength="4"></div>
                <button type="submit" class="btn-submit">
                    Atualizar Senha
            </form>
        </div>
    </div>
</body>
</html>