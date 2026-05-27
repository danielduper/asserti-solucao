<?php
session_start();
require 'seguranca.php';

// --- TRAVA DE PERMISSÃO ---
// Se o perfil do usuário logado não for 'admin', bloqueia a exclusão
if ($_SESSION['perfil'] !== 'admin') {
    header("Location: relatorios.php");
    exit;
}

require 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM empresas WHERE id = ?");
        $stmt->execute([$id]);
    } catch(PDOException $e) {
        // Ignora erro
    }
}

header("Location: relatorios.php");
exit;
?>