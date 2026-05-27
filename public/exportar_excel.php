<?php
session_start();
require 'seguranca.php';
require 'conexao.php';

// Mesma lógica de busca do relatorios.php para exportar só o que foi filtrado
$sql = "SELECT * FROM empresas WHERE 1=1";
$params = [];
if (!empty($_GET['nome'])) { $sql .= " AND razao_social LIKE ?"; $params[] = '%' . $_GET['nome'] . '%'; }
if (!empty($_GET['cidade'])) { $sql .= " AND cidade_estado LIKE ?"; $params[] = '%' . $_GET['cidade'] . '%'; }
if (!empty($_GET['cnpj'])) { $sql .= " AND cnpj LIKE ?"; $params[] = '%' . $_GET['cnpj'] . '%'; }

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erro na exportação: " . $e->getMessage());
}

// Configura os cabeçalhos para forçar o navegador a baixar um arquivo CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=relatorio_asserti.csv');

// Abre a saída direto para o navegador
$saida = fopen('php://output', 'w');

// Adiciona o BOM para o Excel do Windows não "quebrar" os acentos em UTF-8
fputs($saida, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));

// Escreve a primeira linha (Cabeçalhos)
fputcsv($saida, ['Razão Social', 'CNPJ', 'Cidade/Estado', 'Telefone', 'Email', 'Faturamento', 'Colaboradores', 'Postos Trabalho', 'Exporta?', 'ESG ODS?'], ';');

// Escreve os dados linha por linha
foreach ($empresas as $emp) {
    $linha = [
        $emp['razao_social'],
        $emp['cnpj'],
        $emp['cidade_estado'],
        $emp['telefone_1'],
        $emp['email'],
        'R$ ' . number_format($emp['faturamento_2025'], 2, ',', '.'),
        $emp['total_colaboradores'],
        $emp['postos_trabalho'],
        $emp['exporta'] ? 'Sim' : 'Não',
        $emp['esg_ods'] ? 'Sim' : 'Não'
    ];
    // O ponto e vírgula ';' garante que o Excel separe as colunas certinho
    fputcsv($saida, $linha, ';');
}

fclose($saida);
exit;
?>