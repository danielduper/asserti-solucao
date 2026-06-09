<?php
session_start();
require 'seguranca.php';
require 'conexao.php';

try {
    $totalEmpresas = $pdo->query("SELECT COUNT(*) FROM empresas")->fetchColumn() ?: 0;
    $totalExportadoras = $pdo->query("SELECT COUNT(*) FROM empresas WHERE exporta = 1")->fetchColumn() ?: 0;
    $totalESG = $pdo->query("SELECT COUNT(*) FROM empresas WHERE esg_ods = 1")->fetchColumn() ?: 0;
    
    $pctExport = $totalEmpresas > 0 ? number_format(($totalExportadoras / $totalEmpresas) * 100, 1) : 0;
    $pctEsg = $totalEmpresas > 0 ? number_format(($totalESG / $totalEmpresas) * 100, 1) : 0;

    // GRÁFICO DIREITO: CIDADES (Pega tudo ANTES do hífen)
    // O filtro LIKE '% - %' garante que ele só pegue dados formatados corretamente pela nossa nova tela
    $stmtCidades = $pdo->query("SELECT TRIM(SUBSTRING_INDEX(cidade_estado, ' - ', 1)) as cidade, COUNT(*) as qtd 
                                FROM empresas WHERE cidade_estado LIKE '% - %' 
                                GROUP BY cidade ORDER BY qtd DESC LIMIT 5");
    $labelsCidades = []; $dadosCidades = [];
    while ($linha = $stmtCidades->fetch(PDO::FETCH_ASSOC)) { 
        $labelsCidades[] = $linha['cidade']; 
        $dadosCidades[] = $linha['qtd']; 
    }

    // GRÁFICO ESQUERDO: ESTADOS (Pega a SIGLA DEPOIS do hífen)
    $stmtEstados = $pdo->query("SELECT TRIM(SUBSTRING_INDEX(cidade_estado, ' - ', -1)) as estado, COUNT(*) as qtd 
                                FROM empresas WHERE cidade_estado LIKE '% - %' 
                                GROUP BY estado ORDER BY qtd DESC LIMIT 5");
    $labelsEstados = []; $dadosEstados = [];
    while ($linha = $stmtEstados->fetch(PDO::FETCH_ASSOC)) {
        $labelsEstados[] = $linha['estado'];
        $dadosEstados[] = $linha['qtd'];
    }

} catch (PDOException $e) { die("Erro: " . $e->getMessage()); }
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Asserti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #171717 !important; color: #e5e7eb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; overflow-x: hidden; margin: 0; min-height: 100vh;}
        .sidebar { width: 250px; height: 100vh; background-color: #212121; border-right: 1px solid #333; position: fixed; display: flex; flex-direction: column; z-index: 1000;}
        .sidebar a { color: #888; text-decoration: none; display: block; padding: 12px 25px; font-weight: 500; transition: 0.2s; font-size: 0.95rem; border-radius: 0;}
        .sidebar a:hover, .sidebar a.active { color: #fff; background: #2a2a2a; border-left: 3px solid #3b82f6; }
        .btn-logout { color: #ef4444 !important; margin-top: auto; margin-bottom: 20px; }
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); background-color: #171717; }
        .flat-card { background: #212121 !important; border: 1px solid #333 !important; border-radius: 0 !important; padding: 25px; height: 100%; display: flex; flex-direction: column;}
        .metric-value { font-size: 3.5rem; font-weight: bold; line-height: 1; color: #fff; margin-top: 10px; display: flex; align-items: baseline; justify-content: space-between;}
    </style>
</head>
<body>
    
    <?php include 'menu.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-end mb-4">
            <h5 class="text-white fw-bold m-0" style="font-style: italic;">Dashboard <img src="icones/so_logo.png" width="40"></span></h5>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="flat-card">
                    <p class="mb-0 text-white fw-bold">Total de Empresas</p>
                    <small style="color: #888;">Base de dados atualizada</small>
                    <div class="metric-value"><?= $totalEmpresas ?> <span style="font-size: 1.5rem;" class="text-success">↗</span></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="flat-card">
                    <p class="mb-0 text-white fw-bold">Total de Empresas <span style="color: #22c55e;">Exportadoras</span></p>
                    <small style="color: #888;"><?= $pctExport ?>% do total geral</small>
                    <div class="metric-value"><?= $totalExportadoras ?> <span style="font-size: 1.5rem;" class="text-success">↗</span></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="flat-card">
                    <p class="mb-0 text-white fw-bold">Empresas que praticam <span style="color: #d946ef;">ESG ODS</span></p>
                    <small style="color: #888;"><?= $pctEsg ?>% do total geral</small>
                    <div class="metric-value"><?= $totalESG ?> <span style="font-size: 1.5rem;" class="text-success">↗</span></div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <h6 class="text-white fw-bold mb-3">Principais <span style="color: #ef4444;">Estados</span> em Influência</h6>
                <div class="flat-card" style="height: 350px;">
                    <?php if(empty($labelsEstados)): ?>
                        <div class="text-center text-secondary mt-5">Nenhum dado formatado encontrado.</div>
                    <?php else: ?>
                        <canvas id="chartEstados"></canvas>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-6">
                <h6 class="text-white fw-bold mb-3">Principais <span style="color: #ef4444;">Cidades</span> em Influência</h6>
                <div class="flat-card" style="height: 350px;">
                    <?php if(empty($labelsCidades)): ?>
                        <div class="text-center text-secondary mt-5">Nenhum dado formatado encontrado.</div>
                    <?php else: ?>
                        <canvas id="chartCidades"></canvas>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.color = '#fff';
        Chart.defaults.font.family = "'Segoe UI', sans-serif";

        <?php if(!empty($labelsEstados)): ?>
        new Chart(document.getElementById('chartEstados').getContext('2d'), { 
            type: 'doughnut', 
            data: { labels: <?= json_encode($labelsEstados) ?>, datasets: [{ data: <?= json_encode($dadosEstados) ?>, backgroundColor: ['#1d4ed8','#65a30d','#ea580c','#8b5cf6','#f97316'], borderWidth: 0 }] }, 
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } }, cutout: '50%' } 
        });
        <?php endif; ?>

        <?php if(!empty($labelsCidades)): ?>
        new Chart(document.getElementById('chartCidades').getContext('2d'), { 
            type: 'pie', 
            data: { labels: <?= json_encode($labelsCidades) ?>, datasets: [{ data: <?= json_encode($dadosCidades) ?>, backgroundColor: ['#8b5cf6','#f97316','#22c55e','#eab308','#3b82f6'], borderWidth: 2, borderColor: '#212121' }] }, 
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'left' } } } 
        });
        <?php endif; ?>
    </script>
</body>
</html>