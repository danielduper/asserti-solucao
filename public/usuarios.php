<?php
session_start();
require 'seguranca.php';

if ($_SESSION['perfil'] !== 'admin') { header("Location: dashboard.php"); exit; }
require 'conexao.php';

$mensagem = '';

// LÓGICA DE EXCLUSÃO
if (isset($_GET['excluir'])) {
    $id_excluir = $_GET['excluir'];
    $stmtVerifica = $pdo->prepare("SELECT usuario FROM usuarios WHERE id = ?");
    $stmtVerifica->execute([$id_excluir]);
    $userDel = $stmtVerifica->fetch(PDO::FETCH_ASSOC);

    if ($userDel && $userDel['usuario'] === $_SESSION['usuario_logado']) {
        $mensagem = "<div class='alert alert-warning mt-3 rounded-0' style='background: rgba(234, 179, 8, 0.1); color: #eab308; border: 1px solid #eab308;'>Você não pode excluir a conta que está usando no momento!</div>";
    } else {
        try {
            $pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id_excluir]);
            $mensagem = "<div class='alert alert-success mt-3 rounded-0' style='background: rgba(16,185,129,0.1); color:#22c55e; border:1px solid #22c55e;'>Usuário removido do sistema.</div>";
        } catch(PDOException $e) {
            $mensagem = "<div class='alert alert-danger mt-3 rounded-0' style='background: rgba(239,68,68,0.1); color:#ef4444; border:1px solid #ef4444;'>Erro ao remover usuário.</div>";
        }
    }
}

// LÓGICA DE INSERÇÃO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, senha, perfil) VALUES (?, ?, ?)");
        $stmt->execute([trim($_POST['usuario']), $senha_hash, $_POST['perfil']]);
        $mensagem = "<div class='alert alert-success mt-3 rounded-0' style='background: rgba(16,185,129,0.1); color:#22c55e; border:1px solid #22c55e;'>Novo usuário adicionado com sucesso!</div>";
    } catch(PDOException $e) {
        $mensagem = "<div class='alert alert-danger mt-3 rounded-0' style='background: rgba(239,68,68,0.1); color:#ef4444; border:1px solid #ef4444;'>Erro: Este nome de usuário já está em uso.</div>";
    }
}

// MÉTRICAS TOTAIS
$total_admin = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE perfil = 'admin'")->fetchColumn() ?: 0;
$total_func = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE perfil = 'funcionario'")->fetchColumn() ?: 0;
$total_users = $total_admin + $total_func;

// LÓGICA DE FILTRAGEM E BUSCA
$where_sql = " WHERE 1=1";
$params_busca = [];

if (!empty($_GET['busca_usuario'])) {
    $where_sql .= " AND usuario LIKE ?";
    $params_busca[] = '%' . trim($_GET['busca_usuario']) . '%';
}

if (!empty($_GET['filtro_perfil'])) {
    $where_sql .= " AND perfil = ?";
    $params_busca[] = $_GET['filtro_perfil'];
}

$stmt_lista = $pdo->prepare("SELECT id, usuario, perfil, criado_em FROM usuarios" . $where_sql . " ORDER BY id DESC");
$stmt_lista->execute($params_busca);
$lista_usuarios = $stmt_lista->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Usuários - Asserti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #171717 !important; color: #e5e7eb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; overflow-x: hidden; margin: 0; min-height: 100vh; }
        .sidebar { width: 250px; height: 100vh; background-color: #212121; border-right: 1px solid #333; position: fixed; display: flex; flex-direction: column; z-index: 1000;}
        .sidebar a { color: #888; text-decoration: none; display: block; padding: 12px 25px; font-weight: 500; transition: 0.2s; font-size: 0.95rem; border-radius: 0;}
        .sidebar a:hover, .sidebar a.active { color: #fff; background: #2a2a2a; border-left: 3px solid #3b82f6; }
        .btn-logout { color: #ef4444 !important; margin-top: auto; margin-bottom: 20px; }
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); background-color: #171717; }
        
        .flat-card { background-color: #212121; border: 1px solid #333; border-radius: 0; padding: 30px; }
        .flat-input { background: transparent !important; border: 1px solid #555 !important; color: white !important; border-radius: 0 !important; padding: 12px 15px; }
        .flat-input:focus { border-color: #3b82f6 !important; box-shadow: none !important; }
        .btn-submit { background-color: #3b82f6; color: white; border: none; padding: 12px 25px; border-radius: 0; font-weight: bold; width: 100%; transition: 0.2s;}
        .btn-submit:hover { background-color: #2563eb; }
        .btn-clear { background: transparent; border: 1px solid #555; color: #aaa; padding: 0 20px; border-radius: 0; text-decoration: none; display: flex; align-items: center; justify-content: center; transition: 0.2s;}
        .btn-clear:hover { background: #333; color: #fff; }
        select option { background-color: #212121; color: white; }
        
        .metric-value { font-size: 2.5rem; font-weight: bold; color: #fff; margin-top: 5px; }
        
        .table-custom { width: 100%; border-collapse: collapse; margin-top: 10px;}
        .table-custom th { border-bottom: 1px solid #333; padding: 15px 10px; color: #aaa; text-align: left; font-weight: 600; font-size: 0.9rem;}
        .table-custom td { border-bottom: 1px solid #333; padding: 15px 10px; text-align: left; vertical-align: middle; background-color: transparent; font-size: 0.9rem;}
        .badge-admin { background-color: rgba(59, 130, 246, 0.2); color: #3b82f6; padding: 5px 10px; border-radius: 0; font-size: 0.8rem; font-weight: bold; }
        .badge-func { background-color: rgba(34, 197, 94, 0.2); color: #22c55e; padding: 5px 10px; border-radius: 0; font-size: 0.8rem; font-weight: bold; }
        .btn-acao { text-decoration: none; font-size: 1rem; color: #ef4444; transition: 0.2s;}
        .btn-acao:hover { color: #dc2626; opacity: 0.8;}
    </style>
</head>
<body>
    
    <?php include 'menu.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-end mb-4">
            <h5 class="text-white fw-bold m-0" style="font-style: italic;">Gestão de Acessos <span style="color:#3b82f6;">></span></h5>
        </div>
        
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="flat-card" style="padding: 20px;">
                    <p class="mb-0 text-white fw-bold">Total de Contas</p>
                    <div class="metric-value"><?= $total_users ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="flat-card" style="padding: 20px;">
                    <p class="mb-0 text-white fw-bold" style="color: #3b82f6 !important;">Administradores</p>
                    <div class="metric-value"><?= $total_admin ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="flat-card" style="padding: 20px;">
                    <p class="mb-0 text-white fw-bold" style="color: #22c55e !important;">Funcionários</p>
                    <div class="metric-value"><?= $total_func ?></div>
                </div>
            </div>
        </div>

        <?= $mensagem ?>

        <div class="row g-4 mt-1">
            <div class="col-md-4">
                <div class="flat-card h-100">
                    <h5 class="fw-bold mb-4 text-white">Adicionar Usuário</h5>
                    <form action="usuarios.php" method="POST">
                        <div class="mb-3"><label style="color: #888;" class="form-label small">Nome de Usuário (Login)</label><input type="text" name="usuario" class="form-control flat-input" required autocomplete="off"></div>
                        <div class="mb-3"><label style="color: #888;" class="form-label small">Senha</label><input type="password" name="senha" class="form-control flat-input" required autocomplete="new-password"></div>
                        <div class="mb-4">
                            <label style="color: #888;" class="form-label small">Nível de Permissão</label>
                            <select name="perfil" class="form-select flat-input" required>
                                <option value="funcionario">Funcionário (Acesso Restrito)</option>
                                <option value="admin">Administrador (Acesso Total)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-submit">Criar Conta</button>
                    </form>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="flat-card h-100">
                    <h5 class="fw-bold mb-4 text-white">Contas Registradas</h5>
                    
                    <!-- NOVO SISTEMA DE PESQUISA E FILTRO -->
                    <form method="GET" class="d-flex gap-2 mb-4">
                        <div style="flex-grow: 1;">
                            <input type="text" name="busca_usuario" class="form-control flat-input w-100" placeholder="Buscar por nome de usuário..." value="<?= htmlspecialchars($_GET['busca_usuario'] ?? '') ?>">
                        </div>
                        <div style="width: 220px;">
                            <select name="filtro_perfil" class="form-select flat-input">
                                <option value="">Todos os Perfis</option>
                                <option value="admin" <?= (isset($_GET['filtro_perfil']) && $_GET['filtro_perfil'] == 'admin') ? 'selected' : '' ?>>Administradores</option>
                                <option value="funcionario" <?= (isset($_GET['filtro_perfil']) && $_GET['filtro_perfil'] == 'funcionario') ? 'selected' : '' ?>>Funcionários</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-submit" style="width: auto;">Buscar</button>
                        <a href="usuarios.php" class="btn-clear">Limpar</a>
                    </form>

                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>Perfil</th>
                                    <th>Criado em</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($lista_usuarios) > 0): ?>
                                    <?php foreach ($lista_usuarios as $user): ?>
                                        <tr>
                                            <td class="text-white fw-bold">@<?= htmlspecialchars($user['usuario']) ?></td>
                                            <td>
                                                <?php if($user['perfil'] === 'admin'): ?>
                                                    <span class="badge-admin">Admin</span>
                                                <?php else: ?>
                                                    <span class="badge-func">Funcionário</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="color:#aaa;"><?= date('d/m/Y', strtotime($user['criado_em'])) ?></td>
                                            <td class="text-center">
                                                <?php if($user['usuario'] !== $_SESSION['usuario_logado']): ?>
                                                    <a href="usuarios.php?excluir=<?= $user['id'] ?>" class="btn-acao" onclick="return confirm('Tem certeza que deseja remover o acesso de @<?= htmlspecialchars($user['usuario']) ?>?');" title="Excluir Usuário">
                                                        <img src="icones/retirada.svg" width="18">
                                                    </a>
                                                <?php else: ?>
                                                    <span style="color:#555; font-size: 0.8rem; font-style: italic;">Você</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-secondary">Nenhum usuário encontrado com estes filtros.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>