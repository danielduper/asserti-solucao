<?php
session_start();
require 'seguranca.php';
require 'conexao.php';

// --- CONFIGURAÇÃO DA PAGINAÇÃO ---
$registros_por_pagina = 10; 
$pagina_atual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$offset = ($pagina_atual - 1) * $registros_por_pagina;

// --- LÓGICA DE FILTRAGEM ---
$whereSql = " WHERE 1=1";
$params = [];

if (!empty($_GET['nome'])) { $whereSql .= " AND razao_social LIKE ?"; $params[] = '%' . $_GET['nome'] . '%'; }
if (!empty($_GET['cidade'])) { $whereSql .= " AND cidade_estado LIKE ?"; $params[] = '%' . $_GET['cidade'] . '%'; }
if (!empty($_GET['cnpj'])) { $whereSql .= " AND cnpj LIKE ?"; $params[] = '%' . $_GET['cnpj'] . '%'; }

try {
    $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM empresas" . $whereSql);
    $stmtCount->execute($params);
    $total_filtrado = $stmtCount->fetchColumn();
    $total_paginas = ceil($total_filtrado / $registros_por_pagina);

    $sql = "SELECT * FROM empresas" . $whereSql . " LIMIT $registros_por_pagina OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_banco = $pdo->query("SELECT COUNT(*) FROM empresas")->fetchColumn();

} catch(PDOException $e) { die("Erro: " . $e->getMessage()); }

function linkPagina($pag) {
    $get = $_GET;
    $get['pagina'] = $pag;
    return '?' . http_build_query($get);
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Relatórios - Asserti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #171717 !important; color: #e5e7eb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; overflow-x: hidden; margin: 0; min-height: 100vh; }
        .sidebar { width: 250px; height: 100vh; background-color: #212121; border-right: 1px solid #333; position: fixed; display: flex; flex-direction: column; z-index: 1000;}
        .sidebar a { color: #888; text-decoration: none; display: block; padding: 12px 25px; font-weight: 500; transition: 0.2s; font-size: 0.95rem; border-radius: 0;}
        .sidebar a:hover, .sidebar a.active { color: #fff; background: #2a2a2a; border-left: 3px solid #3b82f6; }
        .btn-logout { color: #ef4444 !important; margin-top: auto; margin-bottom: 20px; }
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); background-color: #171717; }
        
        .filter-input { background: transparent !important; color: white !important; border-radius: 0; padding: 8px 12px; width: 100%; text-align: left; font-size: 0.9rem;}
        .filter-input::placeholder { color: #0dcaf0; }
        .border-ciano { border: 1px solid #0dcaf0 !important; }
        .border-roxo { border: 1px solid #8b5cf6 !important; }
        .filter-input:focus { outline: none; box-shadow: none; }
        
        .btn-outline-roxo { border: 1px solid #8b5cf6; color: #8b5cf6; background: transparent; border-radius: 0; padding: 8px 30px; font-weight: 600; }
        .btn-export { background: transparent; border: 1px solid #d946ef; color: #d946ef; border-radius: 0; padding: 8px 15px; width: 100%; text-align: left; font-size: 0.9rem;}
        
        .table-custom { width: 100%; border-collapse: collapse; margin-top: 10px;}
        .table-custom th { border: 1px solid #333; padding: 15px 10px; color: #aaa; text-align: center; font-weight: 600; font-size: 0.9rem;}
        .table-custom td { border: 1px solid #333; padding: 15px 10px; text-align: center; vertical-align: middle; background-color: #212121; font-size: 0.9rem;}
        .text-success-custom { color: #22c55e !important; font-weight: bold; }
        .text-danger-custom { color: #ef4444 !important; font-weight: bold; }
        
        .btn-acao { text-decoration: none; font-size: 1rem; margin: 0 5px;}
        
        .pagination .page-link { background-color: #2a2a2a; border-color: #444; color: #888; border-radius: 0; margin-right: 5px;}
        .pagination .page-item.active .page-link { background-color: #444; color: #fff; border-color: #444; }

        @media print {
            @page { size: A4 landscape; margin: 15mm; }
            body { background-color: #fff !important; color: #000 !important; }
            .sidebar, form, .pagination-container, .area-filtros-topo, .btn-logout, .coluna-acoes { display: none !important; }
            .main-content { margin-left: 0 !important; width: 100% !important; padding: 0 !important; background-color: #fff !important; }
            
            .print-header { display: block !important; margin-bottom: 20px; text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; }
            .print-header h2 { font-weight: bold; font-style: italic; color: #000; margin: 0; font-size: 24pt;}
            .print-header p { margin: 5px 0 0 0; color: #555; font-size: 10pt; }
            
            .table-custom { border: 1px solid #000 !important; margin-top: 0 !important; border-collapse: collapse !important; width: 100% !important;}
            .table-custom th { border: 1px solid #000 !important; background-color: #f0f0f0 !important; color: #000 !important; font-weight: bold !important; padding: 8px !important; }
            .table-custom td { border: 1px solid #000 !important; background-color: #fff !important; color: #000 !important; padding: 8px !important; font-size: 10pt !important; }
            
            .text-success-custom { color: #059669 !important; }
            .text-danger-custom { color: #dc2626 !important; }
        }
        .print-header { display: none; }
    </style>
</head>
<body>
    
    <?php include 'menu.php'; ?>

    <div class="main-content">
        <div class="print-header">
            <h2>Asserti ></h2>
            <p id="data-hora-pdf">Relatório Oficial de Empresas - Gerado em: ...</p>
        </div>

        <div class="d-flex justify-content-end mb-4 area-filtros-topo">
            <h5 class="text-white fw-bold m-0" style="font-style: italic;">Relatório <span style="color:#3b82f6;">></span></h5>
        </div>
        
        <form method="GET" class="mb-4">
            <p class="text-white fw-bold mb-3">Pesquise por um ou mais filtros:</p>
            <div class="row g-3 align-items-center mb-3">
                <div class="col-md-2"><input type="text" name="nome" class="filter-input border-ciano" placeholder="Nome da empresa" value="<?= htmlspecialchars($_GET['nome'] ?? '') ?>"></div>
                <div class="col-md-2"><input type="text" name="cidade" class="filter-input border-ciano" placeholder="Cidade / Estado" value="<?= htmlspecialchars($_GET['cidade'] ?? '') ?>"></div>
                <div class="col-md-3"><input type="text" class="filter-input border-ciano" placeholder="Número mínimo de colaboradores"></div>
                <div class="col-md-2"><button type="submit" formaction="relatorios.php" class="btn-outline-roxo">Pesquisar</button></div>
                
                <div class="col-md-3 text-end">
                    <button type="button" onclick="gerarPDF()" class="btn-export">
                        <img src="icones/baixar.svg" width="16" style="margin-right: 8px; vertical-align: text-bottom;"> Baixar relatório em PDF
                    </button>
                </div>
            </div>
            <div class="row g-3 align-items-center">
                <div class="col-md-2"><input type="text" name="cnpj" class="filter-input border-ciano" placeholder="CNPJ" value="<?= htmlspecialchars($_GET['cnpj'] ?? '') ?>"></div>
                <div class="col-md-2"><input type="text" class="filter-input border-ciano" placeholder="Num. Postos de trabalho"></div>
                <div class="col-md-3"><input type="text" class="filter-input border-ciano" placeholder="Número máximo de colaboradores"></div>
                <div class="col-md-2"></div>
                
                <div class="col-md-3 text-end">
                    <button type="submit" formaction="exportar_excel.php" class="btn-export">
                        <img src="icones/baixar.svg" width="16" style="margin-right: 8px; vertical-align: text-bottom;"> Baixar relatório em EXCEL
                    </button>
                </div>
            </div>
        </form>

        <div class="d-flex justify-content-between mb-2 mt-5 area-filtros-topo">
            <small style="color:#d946ef; font-style: italic;">Mostrando <?= count($empresas) ?> de <?= $total_banco ?> registros</small>
            <small style="color:#888; font-style: italic;">*Para um relatório personalizado, faça uma busca utilizando os filtros*</small>
        </div>
        
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Nome Empresa</th>
                    <th>Cidade / Estado</th>
                    <th>CNPJ</th>
                    <th>Colaboradores</th>
                    <th>Postos de trabalho</th>
                    <th>Faturamento no último ano</th>
                    <th>Possui práticas ESG alinhadas aos ODS?</th>
                    <th>Exporta?</th>
                    <th class="coluna-acoes">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($empresas) > 0): ?>
                    <?php foreach ($empresas as $emp): ?>
                        <tr>
                            <td class="text-white fw-bold"><?= htmlspecialchars($emp['razao_social']) ?></td>
                            <td style="color:#aaa;"><?= htmlspecialchars($emp['cidade_estado']) ?></td>
                            <td style="color:#aaa;"><?= htmlspecialchars($emp['cnpj']) ?></td>
                            <td style="color:#aaa;"><?= number_format($emp['total_colaboradores'], 0, ',', '.') ?></td>
                            <td style="color:#aaa;"><?= $emp['postos_trabalho'] ?></td>
                            <td style="color:#aaa;">R$ <?= number_format($emp['faturamento_2025'], 0, ',', '.') ?></td>
                            <td class="<?= $emp['esg_ods']?'text-success-custom':'text-danger-custom' ?>"><?= $emp['esg_ods']?'Sim':'Não' ?></td>
                            <td class="<?= $emp['exporta']?'text-success-custom':'text-danger-custom' ?>"><?= $emp['exporta']?'Sim':'Não' ?></td>
                            <td class="coluna-acoes">
                                <a href="editar.php?id=<?= $emp['id'] ?>" class="btn-acao" title="Editar">
                                    <img src="icones/editar.svg" width="18">
                                </a>
                                <?php if($_SESSION['perfil'] === 'admin'): ?>
                                    <a href="excluir.php?id=<?= $emp['id'] ?>" class="btn-acao" title="Excluir" onclick="return confirm('Excluir <?= htmlspecialchars($emp['razao_social']) ?>?');">
                                        <img src="icones/remove.svg" width="18">
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center py-4" style="color: #666;">Nenhuma empresa encontrada.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if($total_paginas > 1): ?>
        <div class="pagination-container d-flex justify-content-between align-items-center mt-3">
            <ul class="pagination m-0">
                <?php for($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?= ($pagina_atual == $i) ? 'active' : '' ?>"><a class="page-link" href="<?= linkPagina($i) ?>"><?= $i ?></a></li>
                <?php endfor; ?>
                <li class="page-item"><a class="page-link" style="background: transparent; border: none;" href="<?= linkPagina($total_paginas) ?>">Última página</a></li>
            </ul>
        </div>
        <?php endif; ?>

    </div>

    <script>
        function gerarPDF() {
            const dataAtual = new Date();
            const dataFormatada = dataAtual.toLocaleDateString('pt-BR');
            const horaFormatada = dataAtual.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('data-hora-pdf').innerText = 'Relatório Oficial de Empresas - Gerado em: ' + dataFormatada + ' às ' + horaFormatada;
            window.print();
        }
    </script>
</body>
</html>