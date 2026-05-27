<?php
session_start();
require 'seguranca.php';
require 'conexao.php';

$mensagem = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['arquivo_csv'])) {
    $arquivo = $_FILES['arquivo_csv']['tmp_name'];
    if (empty($arquivo)) { $mensagem = "<div class='alert alert-danger rounded-0'>Selecione um arquivo.</div>"; } 
    else {
        $handle = fopen($arquivo, "r");
        if ($handle !== FALSE) {
            fgetcsv($handle, 1000, ";");
            $sucesso = 0; $erros = 0;
            while (($dados = fgetcsv($handle, 1000, ";")) !== FALSE) {
                try {
                    $faturamento = str_replace(',', '.', preg_replace('/[^0-9,]/', '', $dados[5] ?? '0'));
                    $exporta = (strtolower(trim($dados[8] ?? 'n')) == 's') ? 1 : 0;
                    $esg = (strtolower(trim($dados[9] ?? 'n')) == 's') ? 1 : 0;
                    if (empty($dados[0]) || empty($dados[1])) continue;
                    $pdo->prepare("INSERT INTO empresas (razao_social, cnpj, cidade_estado, telefone_1, email, faturamento_2025, total_colaboradores, postos_trabalho, exporta, esg_ods) VALUES (?,?,?,?,?,?,?,?,?,?)")
                        ->execute([$dados[0], $dados[1], $dados[2], $dados[3], $dados[4], $faturamento ?: 0, (int)$dados[6], (int)$dados[7], $exporta, $esg]);
                    $sucesso++;
                } catch(PDOException $e) { $erros++; }
            }
            fclose($handle);
            $mensagem = "<div class='alert alert-success mt-3 rounded-0' style='background: rgba(16,185,129,0.1); color:#22c55e; border:1px solid #22c55e;'>✅ $sucesso importadas, ❌ $erros erros (duplicados).</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Importar - Asserti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #171717 !important; color: #e5e7eb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; overflow-x: hidden; margin: 0; min-height: 100vh; }
        .sidebar { width: 250px; height: 100vh; background-color: #212121; border-right: 1px solid #333; position: fixed; display: flex; flex-direction: column; z-index: 1000;}
        .sidebar a { color: #888; text-decoration: none; display: block; padding: 12px 25px; font-weight: 500; transition: 0.2s; font-size: 0.95rem; border-radius: 0;}
        .sidebar a:hover, .sidebar a.active { color: #fff; background: #2a2a2a; border-left: 3px solid #3b82f6; }
        .btn-logout { color: #ef4444 !important; margin-top: auto; margin-bottom: 20px; }
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); background-color: #171717; }
        
        .flat-card { background-color: #212121; border: 1px solid #333; border-radius: 0; padding: 40px; }
        .flat-input { background: transparent !important; border: 1px dashed #555 !important; color: white !important; border-radius: 0 !important; padding: 20px; }
        .btn-submit { background-color: #059669; color: #f0f0f0; border: none; padding: 12px 25px; border-radius: 0; font-weight: bold; }
    </style>
</head>
<body>
    
    <?php include 'menu.php'; ?>

    <div class="main-content">
        <div class="flat-card">
            <h3 class="fw-bold mb-3 text-white">Upload de Dados (CSV)</h3>
            <?= $mensagem ?>
            <form action="importar.php" method="POST" enctype="multipart/form-data" class="mt-4">
                <div class="mb-4">
                    <label class="form-label text-white fw-bold mb-3">Arquivo CSV (Separado por Ponto e Vírgula):</label>
                    <input class="form-control flat-input" type="file" name="arquivo_csv" accept=".csv" required>
                </div>
                <div class="text-end mt-4">
                    <button type="submit" class="btn-submit">
                        <img src="icones/documento.svg" width="18" style="margin-right: 8px; vertical-align: middle;"> Injetar Dados
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>