<?php
session_start();
require 'seguranca.php';
require 'conexao.php';

$mensagem = ''; $empresa = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM empresas WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $empresa = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$empresa) { header("Location: relatorios.php"); exit; }
    
    // Tenta separar a string "Cidade - UF" para preencher os selects
    $partes_local = explode(' - ', $empresa['cidade_estado']);
    $cidade_atual = isset($partes_local[0]) ? trim($partes_local[0]) : '';
    $estado_atual = isset($partes_local[1]) ? trim($partes_local[1]) : '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $faturamento = str_replace(',', '.', preg_replace('/[^0-9,]/', '', $_POST['faturamento']));
        $cidade_estado = trim($_POST['cidade']) . ' - ' . trim($_POST['estado']);
        
        $sql = "UPDATE empresas SET razao_social=?, cnpj=?, cidade_estado=?, telefone_1=?, telefone_2=?, email=?, faturamento_2025=?, total_colaboradores=?, postos_trabalho=?, exporta=?, esg_ods=? WHERE id=?";
        $pdo->prepare($sql)->execute([$_POST['razao_social'], $_POST['cnpj'], $cidade_estado, $_POST['telefone_1'], $_POST['telefone_2'], $_POST['email'], $faturamento, $_POST['colaboradores'], $_POST['postos'], isset($_POST['exporta'])?1:0, isset($_POST['esg_ods'])?1:0, $_POST['id']]);
        $mensagem = "<div class='alert alert-success mt-3 rounded-0' style='background: rgba(16,185,129,0.1); color:#22c55e; border:1px solid #22c55e;'>Registo atualizado!</div>";
        
        $stmt = $pdo->prepare("SELECT * FROM empresas WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $empresa = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $partes_local = explode(' - ', $empresa['cidade_estado']);
        $cidade_atual = isset($partes_local[0]) ? trim($partes_local[0]) : '';
        $estado_atual = isset($partes_local[1]) ? trim($partes_local[1]) : '';
    } catch (PDOException $e) { $mensagem = "<div class='alert alert-danger mt-3 rounded-0' style='background: rgba(239,68,68,0.1); color:#ef4444; border:1px solid #ef4444;'>Erro: " . $e->getMessage() . "</div>"; }
}
?>
<!DOCTYPE html>
<html lang="pt-PT" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Editar Empresa - Asserti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #171717 !important; color: #e5e7eb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; overflow-x: hidden; margin: 0; min-height: 100vh; }
        .sidebar { width: 250px; height: 100vh; background-color: #212121; border-right: 1px solid #333; position: fixed; display: flex; flex-direction: column; z-index: 1000;}
        .sidebar a { color: #888; text-decoration: none; display: block; padding: 12px 25px; font-weight: 500; transition: 0.2s; font-size: 0.95rem; border-radius: 0;}
        .sidebar a:hover, .sidebar a.active { color: #fff; background: #2a2a2a; border-left: 3px solid #3b82f6; }
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); background-color: #171717; }
        .flat-card { background-color: #212121; border: 1px solid #333; border-radius: 0; padding: 40px; }
        .flat-input { background: transparent !important; border: 1px solid #555 !important; color: white !important; border-radius: 0 !important; padding: 15px; font-size: 0.95rem;}
        .flat-input:focus { border-color: #8b5cf6 !important; box-shadow: none !important; }
        select option { background-color: #212121; color: white; }
        .btn-clear { background-color: transparent; color: #aaa; border: 1px solid #555; padding: 12px 25px; border-radius: 0; font-weight: 600; text-decoration: none; }
        .btn-submit { background-color: #8b5cf6; color: white; border: none; padding: 12px 25px; border-radius: 0; font-weight: 600; }
        hr { border-color: #333; margin: 40px 0 20px 0;}
    </style>
</head>
<body>
    
    <?php include 'menu.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-white fw-bold m-0" style="font-style: italic;">Edição <span style="color:#3b82f6;">></span></h5>
            <a href="relatorios.php" style="color: #3b82f6; text-decoration:none;">← Voltar para a lista</a>
        </div>
        
        <div class="flat-card">
            <h3 class="fw-bold mb-0 text-white">Editar Empresa</h3>
            <?= $mensagem ?>
            
            <form action="editar.php?id=<?= $empresa['id'] ?>" method="POST" class="mt-4">
    <input type="hidden" name="id" value="<?= $empresa['id'] ?>">
    
    <div class="row g-4 mb-4">
        <div class="col-md-4" style="position: relative;">
            <label style="color: #888;" class="form-label small">Razão Social</label>
            <img src="icones/predio.svg" style="position: absolute; left: 25px; top: 68%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="text" name="razao_social" class="form-control flat-input" value="<?= htmlspecialchars($empresa['razao_social']) ?>" style="padding-left: 45px;" required>
        </div>
        <div class="col-md-4" style="position: relative;">
            <label style="color: #888;" class="form-label small">CNPJ</label>
            <img src="icones/predio.svg" style="position: absolute; left: 25px; top: 68%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="text" name="cnpj" class="form-control flat-input" value="<?= htmlspecialchars($empresa['cnpj']) ?>" style="padding-left: 45px;" required>
        </div>
        <div class="col-md-2" style="position: relative;">
            <label style="color: #888;" class="form-label small">Estado</label>
            <img src="icones/localizacao.svg" style="position: absolute; left: 25px; top: 68%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <select name="estado" id="estado" class="form-select flat-input" style="padding-left: 45px;" required><option value="">Carregando...</option></select>
        </div>
        <div class="col-md-2" style="position: relative;">
            <label style="color: #888;" class="form-label small">Cidade</label>
            <img src="icones/localizacao.svg" style="position: absolute; left: 25px; top: 68%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <select name="cidade" id="cidade" class="form-select flat-input" style="padding-left: 45px;" required disabled><option value="">Aguardando UF...</option></select>
        </div>
    </div>
    
    <div class="row g-4 mb-4">
        <div class="col-md-4" style="position: relative;">
            <label style="color: #888;" class="form-label small">Faturamento (R$)</label>
            <img src="icones/sifrao.svg" style="position: absolute; left: 25px; top: 68%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="text" name="faturamento" class="form-control flat-input" value="<?= number_format($empresa['faturamento_2025'], 2, ',', '.') ?>" style="padding-left: 45px;" required>
        </div>
        <div class="col-md-4" style="position: relative;">
            <label style="color: #888;" class="form-label small">Colaboradores</label>
            <img src="icones/pessoas.svg" style="position: absolute; left: 25px; top: 68%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="number" name="colaboradores" class="form-control flat-input" value="<?= $empresa['total_colaboradores'] ?>" style="padding-left: 45px;" required>
        </div>
        <div class="col-md-4" style="position: relative;">
            <label style="color: #888;" class="form-label small">Postos de Trabalho</label>
            <img src="icones/casa.svg" style="position: absolute; left: 25px; top: 68%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="number" name="postos" class="form-control flat-input" value="<?= $empresa['postos_trabalho'] ?>" style="padding-left: 45px;" required>
        </div>
    </div>
    <div class="row g-4 mb-5">
        <div class="col-md-4" style="position: relative;">
            <label style="color: #888;" class="form-label small">Telefone 1</label>
            <img src="icones/telefone.svg" style="position: absolute; left: 25px; top: 68%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="text" name="telefone_1" class="form-control flat-input" value="<?= htmlspecialchars($empresa['telefone_1']) ?>" style="padding-left: 45px;">
        </div>
        <div class="col-md-4" style="position: relative;">
            <label style="color: #888;" class="form-label small">Telefone 2</label>
            <img src="icones/telefone.svg" style="position: absolute; left: 25px; top: 68%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="text" name="telefone_2" class="form-control flat-input" value="<?= htmlspecialchars($empresa['telefone_2']) ?>" style="padding-left: 45px;">
        </div>
        <div class="col-md-4" style="position: relative;">
            <label style="color: #888;" class="form-label small">E-mail</label>
            <img src="icones/carta.svg" style="position: absolute; left: 25px; top: 68%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="email" name="email" class="form-control flat-input" value="<?= htmlspecialchars($empresa['email']) ?>" style="padding-left: 45px;">
        </div>
    </div>
    
    <div class="row align-items-center mb-2">
        <div class="col-md-12 d-flex justify-content-end gap-5">
            <div class="form-check form-switch d-flex align-items-center gap-3">
                <label class="form-check-label text-white fw-bold m-0">Exportadora</label>
                <input class="form-check-input m-0" type="checkbox" name="exporta" <?= $empresa['exporta'] ? 'checked' : '' ?> style="transform: scale(1.3);">
            </div>
            <div class="form-check form-switch d-flex align-items-center gap-3">
                <label class="form-check-label text-white fw-bold m-0">Práticas ESG</label>
                <input class="form-check-input m-0" type="checkbox" name="esg_ods" <?= $empresa['esg_ods'] ? 'checked' : '' ?> style="transform: scale(1.3);">
            </div>
        </div>
    </div>
    
    <hr>
    
    <div class="d-flex justify-content-end gap-3 mt-4">
        <a href="relatorios.php" class="btn-clear">Cancelar</a>
        <button type="submit" class="btn-submit">Salvar Alterações</button>
    </div>
</form>
        </div>
    </div>

    <script>
        const estadoSelect = document.getElementById('estado');
        const cidadeSelect = document.getElementById('cidade');
        
        const estadoSalvo = "<?= $estado_atual ?>";
        const cidadeSalva = "<?= $cidade_atual ?>";

        // 1. Carregar Estados
        fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome')
            .then(response => response.json())
            .then(estados => {
                estadoSelect.innerHTML = '<option value=""> Estado*</option>';
                estados.forEach(estado => {
                    const option = document.createElement('option');
                    option.value = estado.sigla;
                    option.textContent = estado.nome;
                    if(estado.sigla === estadoSalvo) option.selected = true;
                    estadoSelect.appendChild(option);
                });
                
                // Se já tinha estado salvo, dispara o evento para carregar a cidade
                if(estadoSalvo) {
                    estadoSelect.dispatchEvent(new Event('change'));
                }
            });

        // 2. Carregar Cidades
        estadoSelect.addEventListener('change', function() {
            const uf = this.value;
            cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
            cidadeSelect.disabled = true;

            if (uf) {
                fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios`)
                    .then(response => response.json())
                    .then(cidades => {
                        cidadeSelect.innerHTML = '<option value=""> Cidade*</option>';
                        cidades.forEach(cidade => {
                            const option = document.createElement('option');
                            option.value = cidade.nome;
                            option.textContent = cidade.nome;
                            if(cidade.nome === cidadeSalva) option.selected = true;
                            cidadeSelect.appendChild(option);
                        });
                        cidadeSelect.disabled = false;
                    });
            } else {
                cidadeSelect.innerHTML = '<option value="">📍 Cidade*</option>';
            }
        });
    </script>
</body>
</html>