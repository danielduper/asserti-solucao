<?php
if (!isset($_SESSION['usuario_logado'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>