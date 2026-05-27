-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/05/2026 às 21:24
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `asserti_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `razao_social` varchar(150) NOT NULL,
  `nome_fantasia` varchar(150) DEFAULT NULL,
  `cnpj` varchar(18) NOT NULL,
  `cidade_estado` varchar(100) NOT NULL,
  `telefone_1` varchar(20) DEFAULT NULL,
  `telefone_2` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tipo_servico` varchar(100) DEFAULT NULL,
  `faturamento_2025` decimal(15,2) NOT NULL,
  `postos_trabalho` int(11) NOT NULL,
  `total_colaboradores` int(11) NOT NULL,
  `exporta` tinyint(1) DEFAULT 0,
  `esg_ods` tinyint(1) DEFAULT 0,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresas`
--

INSERT INTO `empresas` (`id`, `razao_social`, `nome_fantasia`, `cnpj`, `cidade_estado`, `telefone_1`, `telefone_2`, `email`, `tipo_servico`, `faturamento_2025`, `postos_trabalho`, `total_colaboradores`, `exporta`, `esg_ods`, `criado_em`) VALUES
(3, 'Liberdade financeira', NULL, '123123124213123', 'Assis - SP', '1499878762', '', 'davi@hotmail.com', NULL, 200000.00, 312, 2, 1, 0, '2026-04-14 17:08:42'),
(4, 'Tray Tecnologia', NULL, '08.710.656/0001-24', 'Marília - SP', '(14) 3402-2200', NULL, 'contato@tray.com.br', NULL, 150000000.00, 600, 800, 1, 1, '2026-05-27 19:05:35'),
(5, 'Paschoalotto Serviços', NULL, '05.823.123/0001-99', 'Marília - SP', '(14) 3401-0000', NULL, 'rh@paschoalotto.com.br', NULL, 350000000.00, 2500, 3000, 0, 1, '2026-05-27 19:05:35'),
(6, 'Universidade de Marília - UNIMAR', NULL, '44.474.682/0001-44', 'Marília - SP', '(14) 2105-4000', NULL, 'reitoria@unimar.br', NULL, 85000000.00, 500, 850, 0, 1, '2026-05-27 19:05:35'),
(7, 'Totvs S.A.', NULL, '53.113.791/0001-22', 'São Paulo - SP', '(11) 2099-7000', NULL, 'ri@totvs.com.br', NULL, 800000000.00, 5000, 7500, 1, 1, '2026-05-27 19:05:35'),
(8, 'Locaweb Serviços de Internet', NULL, '02.351.877/0001-52', 'São Paulo - SP', '(11) 3544-0000', NULL, 'contato@locaweb.com.br', NULL, 450000000.00, 1200, 1500, 1, 1, '2026-05-27 19:05:35'),
(9, 'Boa Vista Serviços', NULL, '11.758.133/0001-91', 'Campinas - SP', '(19) 3003-0000', NULL, 'dados@boavista.com.br', NULL, 220000000.00, 800, 950, 0, 1, '2026-05-27 19:05:35'),
(10, 'Yaman Tecnologia', NULL, '14.123.456/0001-00', 'Barueri - SP', '(11) 4000-1111', NULL, 'hello@yaman.com.br', NULL, 45000000.00, 150, 200, 1, 1, '2026-05-27 19:05:35'),
(11, 'InovaPulse COMP.', NULL, '25.079.645/0001-92', 'Ourinhos - SP', '(14) 3322-0000', NULL, 'contato@inovapulse.com.br', NULL, 4131000.00, 15, 15, 1, 1, '2026-05-27 19:05:35'),
(12, 'NovaTech Solutions', NULL, '98.765.432/0001-10', 'Curitiba - PR', '(41) 3333-4444', NULL, 'admin@novatech.com', NULL, 1250000.00, 8, 8, 0, 0, '2026-05-27 19:05:35'),
(13, 'BlueWave Systems', NULL, '45.112.334/0001-56', 'Londrina - PR', '(43) 3020-1010', NULL, 'sac@bluewave.com.br', NULL, 3780000.00, 20, 20, 0, 1, '2026-05-27 19:05:35'),
(14, 'UrbanGrid Tech', NULL, '77.888.999/0001-22', 'Bauru - SP', '(14) 3232-5555', NULL, 'contato@urbangrid.com.br', NULL, 980000.00, 5, 5, 0, 0, '2026-05-27 19:05:35'),
(15, 'NexaLog Logística e Tech', NULL, '11.223.344/0001-55', 'Guarulhos - SP', '(11) 2444-8888', NULL, 'log@nexalog.com.br', NULL, 2150000.00, 11, 11, 1, 0, '2026-05-27 19:05:35'),
(16, 'AlphaWare Softwares', NULL, '66.554.433/0001-77', 'Porto Alegre - RS', '(51) 3333-7777', NULL, 'dev@alphaware.com.br', NULL, 3050000.00, 21, 21, 1, 1, '2026-05-27 19:05:35'),
(17, 'QuantumSoft', NULL, '55.444.333/0001-88', 'Brasília - DF', '(61) 3222-9999', NULL, 'hello@quantumsoft.gov.br', NULL, 2430000.00, 14, 14, 0, 1, '2026-05-27 19:05:35'),
(18, 'IronClad Systems', NULL, '88.777.666/0001-99', 'Goiânia - GO', '(62) 3222-1111', NULL, 'sec@ironclad.com.br', NULL, 1120000.00, 11, 11, 0, 1, '2026-05-27 19:05:35'),
(19, 'Vertex Dynamics', NULL, '34.556.778/0001-12', 'Salvador - BA', '(71) 3333-2222', NULL, 'vendas@vertex.com.br', NULL, 2980000.00, 16, 16, 1, 0, '2026-05-27 19:05:35'),
(20, 'Marilan Alimentos S.A.', NULL, '04.111.222/0001-33', 'Marília - SP', '(14) 3401-1000', NULL, 'contato@marilan.com.br', NULL, 500000000.00, 1500, 2000, 1, 1, '2026-05-27 19:08:25'),
(21, 'Dori Alimentos', NULL, '05.222.333/0001-44', 'Marília - SP', '(14) 3402-2000', NULL, 'sac@dori.com.br', NULL, 450000000.00, 1200, 1600, 1, 1, '2026-05-27 19:08:25'),
(22, 'Tauste Supermercados', NULL, '06.333.444/0001-55', 'Marília - SP', '(14) 3402-3000', NULL, 'adm@tauste.com.br', NULL, 800000000.00, 2500, 3000, 0, 1, '2026-05-27 19:08:25'),
(23, 'Associação Mariliense de Transporte Urbano', NULL, '07.444.555/0001-66', 'Marília - SP', '(14) 3402-4000', NULL, 'contato@amtu.com.br', NULL, 35000000.00, 300, 400, 0, 1, '2026-05-27 19:08:25'),
(24, 'Jacto Máquinas Agrícolas', NULL, '08.555.666/0001-77', 'Pompeia - SP', '(14) 3405-2000', NULL, 'vendas@jacto.com.br', NULL, 1200000000.00, 4000, 5000, 1, 1, '2026-05-27 19:08:25'),
(25, 'WEG S.A.', NULL, '09.666.777/0001-88', 'Jaraguá do Sul - SC', '(47) 3276-4000', NULL, 'info@weg.net', NULL, 2500000000.00, 20000, 30000, 1, 1, '2026-05-27 19:08:25'),
(26, 'Embraer', NULL, '10.777.888/0001-99', 'São José dos Campos - SP', '(12) 3927-1000', NULL, 'press@embraer.com', NULL, 4500000000.00, 15000, 18000, 1, 1, '2026-05-27 19:08:25'),
(27, 'Natura Cosméticos', NULL, '11.888.999/0001-00', 'Cajamar - SP', '(11) 3000-1000', NULL, 'sustentabilidade@natura.net', NULL, 3000000000.00, 8000, 10000, 1, 1, '2026-05-27 19:08:25'),
(28, 'Localiza Rent a Car', NULL, '12.999.000/0001-11', 'Belo Horizonte - MG', '(31) 3247-7000', NULL, 'ri@localiza.com', NULL, 1500000000.00, 6000, 8000, 0, 1, '2026-05-27 19:08:25'),
(29, 'Nubank', NULL, '13.000.111/0001-22', 'São Paulo - SP', '(11) 2000-1000', NULL, 'todeolho@nubank.com.br', NULL, 2000000000.00, 5000, 7000, 0, 1, '2026-05-27 19:08:25'),
(30, 'iFood', NULL, '14.111.222/0001-33', 'Osasco - SP', '(11) 3000-2000', NULL, 'parceiros@ifood.com.br', NULL, 1800000000.00, 3000, 4000, 0, 1, '2026-05-27 19:08:25'),
(31, 'VTEX', NULL, '15.222.333/0001-44', 'Rio de Janeiro - RJ', '(21) 3000-3000', NULL, 'vendas@vtex.com', NULL, 800000000.00, 1200, 1500, 1, 0, '2026-05-27 19:08:25'),
(32, 'RD Station', NULL, '16.333.444/0001-55', 'Florianópolis - SC', '(48) 3000-4000', NULL, 'contato@rdstation.com', NULL, 150000000.00, 600, 800, 1, 1, '2026-05-27 19:08:25'),
(33, 'ContaAzul', NULL, '17.444.555/0001-66', 'Joinville - SC', '(47) 3000-5000', NULL, 'oi@contaazul.com', NULL, 90000000.00, 400, 500, 0, 0, '2026-05-27 19:08:25'),
(34, 'Hotmart', NULL, '18.555.666/0001-77', 'Belo Horizonte - MG', '(31) 3000-6000', NULL, 'suporte@hotmart.com', NULL, 500000000.00, 1000, 1200, 1, 1, '2026-05-27 19:08:25'),
(35, 'Ebanx', NULL, '19.666.777/0001-88', 'Curitiba - PR', '(41) 3000-7000', NULL, 'business@ebanx.com', NULL, 400000000.00, 800, 1000, 1, 1, '2026-05-27 19:08:25'),
(36, 'MadeiraMadeira', NULL, '20.777.888/0001-99', 'Curitiba - PR', '(41) 3000-8000', NULL, 'sac@madeiramadeira.com.br', NULL, 350000000.00, 1200, 1500, 0, 1, '2026-05-27 19:08:25'),
(37, 'Olist', NULL, '21.888.999/0001-00', 'Curitiba - PR', '(41) 3000-9000', NULL, 'vendas@olist.com', NULL, 120000000.00, 500, 600, 1, 1, '2026-05-27 19:08:25'),
(38, 'Zenvia', NULL, '22.999.000/0001-11', 'Porto Alegre - RS', '(51) 3000-1000', NULL, 'hello@zenvia.com', NULL, 200000000.00, 700, 900, 1, 1, '2026-05-27 19:08:25'),
(39, 'Take Blip', NULL, '23.000.111/0001-22', 'Belo Horizonte - MG', '(31) 3000-2000', NULL, 'contato@take.net', NULL, 150000000.00, 600, 800, 1, 0, '2026-05-27 19:08:25'),
(40, 'Méliuz', NULL, '24.111.222/0001-33', 'Belo Horizonte - MG', '(31) 3000-3000', NULL, 'ri@meliuz.com.br', NULL, 80000000.00, 300, 400, 0, 1, '2026-05-27 19:08:25'),
(41, 'Loggi', NULL, '25.222.333/0001-44', 'Cajamar - SP', '(11) 3000-4000', NULL, 'empresas@loggi.com', NULL, 600000000.00, 2000, 2500, 0, 1, '2026-05-27 19:08:25'),
(42, 'QuintoAndar', NULL, '26.333.444/0001-55', 'São Paulo - SP', '(11) 3000-5000', NULL, 'imprensa@quintoandar.com.br', NULL, 700000000.00, 2500, 3000, 0, 1, '2026-05-27 19:08:25'),
(43, 'Creditas', NULL, '27.444.555/0001-66', 'São Paulo - SP', '(11) 3000-6000', NULL, 'ri@creditas.com', NULL, 450000000.00, 1800, 2200, 0, 0, '2026-05-27 19:08:25'),
(44, 'Gympass', NULL, '28.555.666/0001-77', 'São Paulo - SP', '(11) 3000-7000', NULL, 'corp@gympass.com', NULL, 800000000.00, 1500, 2000, 1, 1, '2026-05-27 19:08:25'),
(45, 'Unico', NULL, '29.666.777/0001-88', 'São Paulo - SP', '(11) 3000-8000', NULL, 'vendas@unico.io', NULL, 250000000.00, 800, 1000, 0, 1, '2026-05-27 19:08:25'),
(46, 'Stone', NULL, '30.777.888/0001-99', 'Rio de Janeiro - RJ', '(21) 3000-9000', NULL, 'ri@stone.com.br', NULL, 1200000000.00, 4000, 5000, 0, 1, '2026-05-27 19:08:25'),
(47, 'PicPay', NULL, '31.888.999/0001-00', 'Vitória - ES', '(27) 3000-1000', NULL, 'ri@picpay.com', NULL, 900000000.00, 3000, 4000, 0, 1, '2026-05-27 19:08:25'),
(48, 'Tembici', NULL, '32.999.000/0001-11', 'Rio de Janeiro - RJ', '(21) 3000-2000', NULL, 'mobilidade@tembici.com.br', NULL, 150000000.00, 600, 800, 1, 1, '2026-05-27 19:08:25'),
(49, 'Raízen', NULL, '33.000.111/0001-22', 'Piracicaba - SP', '(19) 3000-3000', NULL, 'contato@raizen.com', NULL, 5000000000.00, 12000, 15000, 1, 1, '2026-05-27 19:08:25'),
(50, 'Klabin', NULL, '34.111.222/0001-33', 'Telêmaco Borba - PR', '(42) 3000-4000', NULL, 'ri@klabin.com.br', NULL, 3500000000.00, 10000, 12000, 1, 1, '2026-05-27 19:08:25'),
(51, 'Alpargatas', NULL, '35.222.333/0001-44', 'Campina Grande - PB', '(83) 3000-5000', NULL, 'ri@alpargatas.com.br', NULL, 2000000000.00, 8000, 10000, 1, 1, '2026-05-27 19:08:25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` enum('admin','funcionario') DEFAULT 'funcionario',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `senha`, `perfil`, `criado_em`) VALUES
(1, 'admin', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'admin', '2026-04-14 14:09:33'),
(6, 'daniel', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'admin', '2026-05-27 19:05:35'),
(7, 'guilherme', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'admin', '2026-05-27 19:05:35'),
(8, 'kazuo', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'admin', '2026-05-27 19:05:35'),
(9, 'davi_admin', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'admin', '2026-05-27 19:05:35'),
(10, 'professor_unimar', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'admin', '2026-05-27 19:05:35'),
(11, 'operador_cadastro', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:05:35'),
(12, 'analista_dados', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:05:35'),
(13, 'suporte_asserti', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:05:35'),
(14, 'auditor_esg', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:05:35'),
(15, 'comercial', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:05:35'),
(16, 'func_rh', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:08:25'),
(17, 'func_financeiro', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:08:25'),
(18, 'func_marketing', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:08:25'),
(19, 'func_vendas1', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:08:25'),
(20, 'func_vendas2', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:08:25'),
(21, 'func_ti', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:08:25'),
(22, 'func_suporte_n2', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:08:25'),
(23, 'func_auditoria', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:08:25'),
(24, 'func_operacoes', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:08:25'),
(25, 'func_logistica', '$2y$10$fEAJATRse9j8KRIr2e87muJ6/0mBhY4TtF9zdp/99yGWc/8.KM0dW', 'funcionario', '2026-05-27 19:08:25');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
