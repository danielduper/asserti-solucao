<?php
$pagina_atual = basename($_SERVER['PHP_SELF']);
$perfil = $_SESSION['perfil'] ?? 'funcionario';
?>
<div class="sidebar">
    <div class="ms-4 mb-5 mt-5">
        <img src="icones/logo_branca.png" width="200"> 
    </div>
    
    <a href="dashboard.php" class="<?= $pagina_atual == 'dashboard.php' ? 'active' : '' ?>">
        <img src="icones/grafico.svg" width="18" style="margin-right: 10px; vertical-align: middle;"> Dashboard
    </a>
    
    <div style="padding: 15px 25px 5px 25px; color: #666; font-size: 0.8rem; text-transform: uppercase; font-weight: bold; margin-top: 10px;">Empresas</div>
    <a href="relatorios.php" class="<?= $pagina_atual == 'relatorios.php' ? 'active' : '' ?>" style="padding-left: 40px;">
        <img src="icones/empresas_icone.svg" width="16" style="margin-right: 8px; vertical-align: middle; opacity: 0.7;"> Relatório
    </a>
    <a href="cadastro.php" class="<?= $pagina_atual == 'cadastro.php' ? 'active' : '' ?>" style="padding-left: 40px;">
        <img src="icones/adicionar_documento.svg" width="16" style="margin-right: 8px; vertical-align: middle; opacity: 0.7;"> Cadastro
    </a>
    <a href="importar.php" class="<?= $pagina_atual == 'importar.php' ? 'active' : '' ?>" style="padding-left: 40px;">
        <img src="icones/importar.svg" width="16" style="margin-right: 8px; vertical-align: middle; opacity: 0.7;"> Importar CSV
    </a>
    
    <div style="padding: 15px 25px 5px 25px; color: #666; font-size: 0.8rem; text-transform: uppercase; font-weight: bold; margin-top: 10px;">Configurações</div>
    <a href="perfil.php" class="<?= $pagina_atual == 'perfil.php' ? 'active' : '' ?>" style="padding-left: 40px;">
        <img src="icones/meu_perfil.svg" width="16" style="margin-right: 8px; vertical-align: middle; opacity: 0.7;"> Meu Perfil
    </a>
    
    <?php if($perfil === 'admin'): ?>
        <a href="usuarios.php" class="<?= $pagina_atual == 'usuarios.php' ? 'active' : '' ?>" style="padding-left: 40px;">
            <img src="icones/gestao.svg" width="16" style="margin-right: 8px; vertical-align: middle; opacity: 0.7;"> Gestão de Acessos
        </a>
    <?php endif; ?>
    
    <a href="logout.php" class="btn-logout" style="margin-top: auto; margin-bottom: 20px;">
        <img src="icones/logout.svg" width="18" style="margin-right: 10px; vertical-align: middle;"> Sair do sistema
    </a>
</div>