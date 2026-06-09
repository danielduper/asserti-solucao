<?php
session_start();
require 'seguranca.php';
require 'conexao.php';

$mensagem = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $faturamento = str_replace(',', '.', preg_replace('/[^0-9,]/', '', $_POST['faturamento']));
        
        // Junta a cidade e o estado no formato "Cidade - UF" para manter compatibilidade com o banco
        $cidade_estado = trim($_POST['cidade']) . ' - ' . trim($_POST['estado']);
        
        $sql = "INSERT INTO empresas (razao_social, cnpj, cidade_estado, telefone_1, telefone_2, email, faturamento_2025, total_colaboradores, postos_trabalho, exporta, esg_ods) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$_POST['razao_social'], $_POST['cnpj'], $cidade_estado, $_POST['telefone_1'], $_POST['telefone_2'], $_POST['email'], $faturamento, $_POST['colaboradores'], $_POST['postos'], isset($_POST['exporta'])?1:0, isset($_POST['esg_ods'])?1:0]);
        $mensagem = "<div class='alert alert-success mt-3 rounded-0' style='background: rgba(16,185,129,0.1); color:#22c55e; border:1px solid #22c55e;'>Sucesso! Empresa cadastrada.</div>";
    } catch (PDOException $e) { $mensagem = "<div class='alert alert-danger mt-3 rounded-0' style='background: rgba(239,68,68,0.1); color:#ef4444; border:1px solid #ef4444;'>Erro: CNPJ duplicado ou erro de dados.</div>"; }
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Asserti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #171717 !important; color: #e5e7eb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; overflow-x: hidden; margin: 0; min-height: 100vh; }
        .sidebar { width: 250px; height: 100vh; background-color: #212121; border-right: 1px solid #333; position: fixed; display: flex; flex-direction: column; z-index: 1000;}
        .sidebar a { color: #888; text-decoration: none; display: block; padding: 12px 25px; font-weight: 500; transition: 0.2s; font-size: 0.95rem; border-radius: 0;}
        .sidebar a:hover, .sidebar a.active { color: #fff; background: #2a2a2a; border-left: 3px solid #3b82f6; }
        .btn-logout { color: #ef4444 !important; margin-top: auto; margin-bottom: 20px; }
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); background-color: #171717; }
        
        .flat-card { background-color: #212121; border: 1px solid #333; border-radius: 0; padding: 40px; }
        .flat-input { background: transparent !important; border: 1px solid #555 !important; color: white !important; border-radius: 0 !important; padding: 15px; font-size: 0.95rem;}
        .flat-input:focus { border-color: #8b5cf6 !important; box-shadow: none !important; }
        .flat-input::placeholder { color: #aaa; }
        select option { background-color: #212121; color: white; }
        
        .btn-green { background-color: #059669; color: white; border: none; padding: 10px 20px; border-radius: 0; font-weight: 600; font-size: 0.9rem;}
        .btn-clear { background-color: #ef4444; color: white; border: none; padding: 12px 25px; border-radius: 0; font-weight: 600; }
        .btn-submit { background-color: #8b5cf6; color: white; border: none; padding: 12px 25px; border-radius: 0; font-weight: 600; }
        
        .form-check-input:checked { background-color: #8b5cf6; border-color: #8b5cf6; }
        .asterisk { color: #ef4444; }
        hr { border-color: #333; margin: 40px 0 20px 0;}
    </style>
</head>
<body>
    
    <?php include 'menu.php'; ?>

    <div class="main-content">
        <div class="flat-card">
            <h3 class="fw-bold mb-4 text-white">Cadastro de empresa</h3>
            <?= $mensagem ?>
            
            <form action="cadastro.php" method="POST" class="mt-4">
    <div class="row g-4 mb-4">
        <div class="col-md-4" style="position: relative;">
            <img src="icones/predio.svg" style="position: absolute; left: 25px; top: 50%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="text" name="razao_social" class="form-control flat-input" placeholder="Digite a razão social*" style="padding-left: 45px;" required>
        </div>
        <div class="col-md-4" style="position: relative;">
            <img src="icones/predio.svg" style="position: absolute; left: 25px; top: 50%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="text" name="cnpj" class="form-control flat-input" placeholder="Digite o CNPJ*" style="padding-left: 45px;" required>
        </div>
        
        <div class="col-md-2" style="position: relative;">
            <img src="icones/localizacao.svg" style="position: absolute; left: 25px; top: 50%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <select name="estado" id="estado" class="form-select flat-input" style="padding-left: 45px;" required>
                <option value="">Estado*</option>
            </select>
        </div>
        <div class="col-md-2" style="position: relative;">
            <img src="icones/localizacao.svg" style="position: absolute; left: 25px; top: 50%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <select name="cidade" id="cidade" class="form-select flat-input" style="padding-left: 45px;" required disabled>
                <option value="">Cidade*</option>
            </select>
        </div>
    </div>
    
    <div class="row g-4 mb-4">
        <div class="col-md-4" style="position: relative;">
            <img src="icones/telefone.svg" style="position: absolute; left: 25px; top: 50%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="text" name="telefone_1" class="form-control flat-input" placeholder="Adicione um telefone para contato" style="padding-left: 45px;">
        </div>
        <div class="col-md-4" style="position: relative;">
            <img src="icones/telefone.svg" style="position: absolute; left: 25px; top: 50%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="text" name="telefone_2" class="form-control flat-input" placeholder="Adicione um segundo telefone" style="padding-left: 45px;">
        </div>
        <div class="col-md-4" style="position: relative;">
            <img src="icones/carta.svg" style="position: absolute; left: 25px; top: 50%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="email" name="email" class="form-control flat-input" placeholder="Adicione um email para contato" style="padding-left: 45px;">
        </div>
    </div>
    <div class="row g-4 mb-5">
        <div class="col-md-4" style="position: relative;">
            <img src="icones/sifrao.svg" style="position: absolute; left: 25px; top: 50%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="text" name="faturamento" class="form-control flat-input" placeholder="Último registro de faturamento*" style="padding-left: 45px;" required>
        </div>
        <div class="col-md-4" style="position: relative;">
            <img src="icones/pessoas.svg" style="position: absolute; left: 25px; top: 50%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="number" name="colaboradores" class="form-control flat-input" placeholder="Quantidade de colaboradores*" style="padding-left: 45px;" required>
        </div>
        <div class="col-md-4" style="position: relative;">
            <img src="icones/casa.svg" style="position: absolute; left: 25px; top: 50%; transform: translateY(-50%); width: 18px; opacity: 0.7; pointer-events: none;">
            <input type="number" name="postos" class="form-control flat-input" placeholder="Quantidade de postos de trabalho*" style="padding-left: 45px;" required>
        </div>
    </div>
    
        <div class="col-md-6 d-flex flex-column align-items-end">
            <div class="form-check form-switch mb-3 d-flex align-items-center gap-3">
                <label class="form-check-label text-white fw-bold m-0">A empresa exporta produtos?<span class="asterisk">*</span></label>
                <input class="form-check-input m-0" type="checkbox" name="exporta" style="transform: scale(1.3);"> 
            </div>
            <div class="form-check form-switch d-flex align-items-center gap-3">
                <label class="form-check-label text-white fw-bold m-0">Práticas ESG alinhadas aos ODS?<span class="asterisk">*</span></label>
                <input class="form-check-input m-0" type="checkbox" name="esg_ods" style="transform: scale(1.3);"> 
            </div>
        </div>
    </div>
    
    <hr>
    
    <div class="row align-items-end">
        <div class="col-md-7">
            <p class="text-secondary m-0" style="font-style: italic; font-size: 0.9rem;">
                Pelo presente instrumento, a empresa declara que as informações são verdadeiras.
            </p>
        </div>
        <div class="col-md-5 d-flex justify-content-end gap-3">
            <button type="reset" class="btn-clear">Limpar preenchimento</button>
            <button type="submit" class="btn-submit">Criar registro</button>
        </div>
    </div>
</form>
        </div>
    </div>

    <script>
        const estadoSelect = document.getElementById('estado');
        const cidadeSelect = document.getElementById('cidade');

        // 1. Carregar Estados do IBGE ao abrir a página
        fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome')
            .then(response => response.json())
            .then(estados => {
                estados.forEach(estado => {
                    const option = document.createElement('option');
                    option.value = estado.sigla; // Salva a Sigla (ex: SP)
                    option.textContent = estado.nome; // Mostra o Nome (ex: São Paulo)
                    estadoSelect.appendChild(option);
                });
            });

        // 2. Carregar Cidades do IBGE quando o Estado mudar
        estadoSelect.addEventListener('change', function() {
            const uf = this.value;
            cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
            cidadeSelect.disabled = true;

            if (uf) {
                fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios`)
                    .then(response => response.json())
                    .then(cidades => {
                        cidadeSelect.innerHTML = '<option value="">📍 Cidade*</option>';
                        cidades.forEach(cidade => {
                            const option = document.createElement('option');
                            option.value = cidade.nome;
                            option.textContent = cidade.nome;
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