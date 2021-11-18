-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 15/07/2019 às 17:18
-- Versão do servidor: 5.6.41
-- Versão do PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `clebinta_wpc_sistema`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagar_grupo`
--

CREATE TABLE `pagar_grupo` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `controle` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `pagar_grupo`
--

INSERT INTO `pagar_grupo` (`id`, `nome`, `controle`, `codigo`) VALUES
(1, 'Cobrança Bancária', '1101', NULL),
(2, 'Depósito', '1102', NULL),
(3, 'Cartao', '1103', NULL),
(4, 'Cheque', '1104', NULL),
(5, 'Rentabilidade', '1105', NULL),
(6, 'Folha Operacional', '3201', NULL),
(7, '13º Salario Operacional', '3202', NULL),
(8, 'Férias Operacional', '3203', NULL),
(9, 'Rescisão Operacional', '3204', NULL),
(10, 'Gratificação Operacional', '3205', NULL),
(11, 'FGTS Operacional', '3206', NULL),
(12, 'INSS Operacional', '3207', NULL),
(13, 'DARF Operacional', '3208', NULL),
(14, 'VA + VR Operacional', '3209', NULL),
(15, 'Vale Transporte Operacional', '3210', NULL),
(16, 'Plano de Saude e Odontologico Operacional', '3211', NULL),
(17, 'Premiação Operacional', '3212', NULL),
(18, 'Contratação e Exame Periódico Operacional', '3213', NULL),
(19, 'Pensão Alimentícia', '3214', NULL),
(20, 'Outros Gastos com Pessoal Operacional', '3215', NULL),
(21, 'Diarias Operacional', '3216', NULL),
(22, 'Comissão Vendedores', '3217', NULL),
(23, 'Comissão Representantes Externos', '3218', NULL),
(24, 'Combustivel Operacional - Diesel', '3300', NULL),
(25, 'Combustivel Operacional - Gasolina Operacional', '3301', NULL),
(26, 'Combustivel Operacional - ARLA', '3302', NULL),
(27, 'Combustível Operacional - Alcool', '3303', NULL),
(28, 'Manutenção de Frota Operacional - Extraordinario', '3304', NULL),
(29, 'Manutenção de Frota Operacional - Pneus Extraordin', '3305', NULL),
(30, 'Manutenção de Frota Operacional - Infectante', '3306', NULL),
(31, 'Manutenção de Frota Operacional -  Pneus Infectant', '3307', NULL),
(32, 'Manutenção de Frota Operacional -  Carros Operacio', '3308', NULL),
(33, 'Manutenção de Frota Operacional -  Óleo Lubrifican', '3309', NULL),
(34, 'Manutenção de Frota Operacional -  Manutenção de C', '3310', NULL),
(35, 'Manutenção de Frota Operacional -  Recapagem de Pn', '3311', NULL),
(36, 'Seguro de Frota', '3312', NULL),
(37, 'IPVA-DUDA Frota Operacional', '3313', NULL),
(38, 'Multas Operacionais', '3314', NULL),
(39, 'Pedágio Operacional', '3315', NULL),
(40, 'Serviço de Guincho', '3316', NULL),
(41, 'Tratamento de Extraordinário', '3317', NULL),
(42, 'Tratamento de Infectante', '3318', NULL),
(43, 'Compra de Conteiner', '3319', NULL),
(44, 'Manutenção de Contêiner', '3320', NULL),
(45, 'Telefone Celular', '3321', NULL),
(46, 'Custo ETE', '3322', NULL),
(47, 'Análise Laboratorial', '3323', NULL),
(48, 'Material de EPI e Uniformes', '3324', NULL),
(49, 'Gráficas - Manifesto - O.S', '3325', NULL),
(50, 'Material de Embalagem', '3326', NULL),
(51, 'Lenha', '3327', NULL),
(52, 'Compra de Máquinas e Equipamentos', '3328', NULL),
(53, 'Papel para Caldeira', '3329', NULL),
(54, 'Manutenção de Autoclave', '3330', NULL),
(55, 'Manutenção de Caldeira', '3331', NULL),
(56, 'Outros Gastos Operacionais', '3332', NULL),
(57, 'Folha Administrativa', '4201', NULL),
(58, '13º Salario Administrativo', '4202', NULL),
(59, 'Férias Administrativo', '4203', NULL),
(60, 'Rescisão Administrativo', '4204', NULL),
(61, 'Gratificação Administrativo', '4205', NULL),
(62, 'FGTS Administrativo', '4206', NULL),
(63, 'INSS Administrativo', '4207', NULL),
(64, 'DARF Administrativo', '4208', NULL),
(65, 'VA + VR Administrativo', '4209', NULL),
(66, 'Vale Transporte Administrativo', '4210', NULL),
(67, 'Plano de Saude e Odontologico Administrativo', '4211', NULL),
(68, 'Premiação Administrativo', '4212', NULL),
(69, 'Contratação e Exame Periódico Administrativo', '4213', NULL),
(70, 'Pensão Alimentícia', '4214', NULL),
(71, 'Outros Gastos com Pessoal Administrativo', '4215', NULL),
(72, 'Curso', '4216', NULL),
(73, 'CIEE + MUDES', '4217', NULL),
(74, 'Sindicato e Associações', '4218', NULL),
(75, 'Prestadores de Serviço', '4219', NULL),
(76, 'Imóvel - Aluguel + IPTU', '4401', NULL),
(77, 'Imóvel - Água', '4402', NULL),
(78, 'Imóvel - Luz', '4403', NULL),
(79, 'Imóvel - Condomínio', '4404', NULL),
(80, 'Imóvel - Obras de Manutenção', '4405', NULL),
(81, 'Imóvel - Limpeza', '4406', NULL),
(82, 'Imóvel - Seguro e Vigilância Predial', '4407', NULL),
(83, 'Imóvel - Extintores e Equipamento de Segurança', '4408', NULL),
(84, 'Engenheiros e Arquitetos', '4409', NULL),
(85, 'Telefone', '4410', NULL),
(86, 'Serviços e Equipamentos de Informática - Manutençã', '4411', NULL),
(87, 'Serviços e Equipamentos de Informática - Licenças ', '4412', NULL),
(88, 'Manutenção Telefônica e Câmeras', '4413', NULL),
(89, 'Manutenção Predial e Equipamentos de Escritório', '4414', NULL),
(90, 'Material de Escritório e Informática', '4415', NULL),
(91, 'Material de Limpeza e Consumo', '4416', NULL),
(92, 'Aluguel de Móveis e Equipamentos', '4417', NULL),
(93, 'Marketing e Publicidade - Treinamentos', '4501', NULL),
(94, 'Marketing e Publicidade - Endomarketing', '4502', NULL),
(95, 'Marketing e Publicidade - Propaganda e Publicidade', '4503', NULL),
(96, 'Contador', '4601', NULL),
(97, 'Cartório', '4602', NULL),
(98, 'Advogado', '4603', NULL),
(99, 'Despesas Judiciais - Processos Judiciais', '4604', NULL),
(100, 'Despesas Judiciais - Despesas com processos', '4605', NULL),
(101, 'Despachantes', '4606', NULL),
(102, 'Segurança Externa', '4607', NULL),
(103, 'Custo Motoboy', '4608', NULL),
(104, 'Correios e Malotes', '4609', NULL),
(105, 'Fretes e Carretos', '4610', NULL),
(106, 'Tarifa Bancária - Tarifa de Cobrança', '4701', NULL),
(107, 'Tarifa Bancária - Outras tarifas', '4702', NULL),
(108, 'Análise de Crédito', '4703', NULL),
(109, 'Impostos e Serviços Ambientais', '4704', NULL),
(110, 'Despesas Comerciais', '4705', NULL),
(111, 'Outras Despesas Administrativo', '4706', NULL),
(112, 'Remuneração dos sócios', '5201', NULL),
(113, 'Plano de Saúde e Odontológico Sócios', '5202', NULL),
(114, 'Cartão Empresarial', '5203', NULL),
(115, 'Despesas com Veículos Sócios', '5204', NULL),
(116, 'Apartamento Eduardo', '5205', NULL),
(117, 'Reembolso de Despesas Sócios', '5206', NULL),
(118, 'Aluguel Cadeg', '5207', NULL),
(119, 'Impostos (IR + IPTU)', '5208', NULL),
(120, 'Outras Despesas Sócios', '5209', NULL),
(121, 'Leasing - Itaú', '6701', NULL),
(122, 'Leasing - Santander', '6702', NULL),
(123, 'Leasing - Bradesco', '6703', NULL),
(124, 'CDC - Banco Volkswagen', '6704', NULL),
(125, 'CDC - Banco Volvo', '6705', NULL),
(126, 'Consórcios', '6706', NULL),
(127, 'Impostos Correntes - ISS', '7801', NULL),
(128, 'Impostos Correntes - DAS', '7802', NULL),
(129, 'Impostos Correntes - INSS', '7803', NULL),
(130, 'Impostos Correntes - PIS + COFINS', '7804', NULL),
(131, 'Impostos Correntes - IR + CSLL', '7805', NULL),
(132, 'Parcelamentos Municipais - ISS', '8801', NULL),
(133, 'Parcelamentos Federais - REFIS da Copa', '8802', NULL),
(134, 'Parcelamentos Federais - DAS SR Santos', '8803', NULL),
(135, 'Parcelamentos Federais - DAS Padrao', '8804', NULL),
(136, 'Parcelamentos Federais - DAS Bapaisa', '8805', NULL),
(137, 'Parcelamentos Federais - INSS', '8806', NULL),
(138, 'Parcelamentos Federais - PIS + COFINS', '8807', NULL),
(139, 'Parcelamentos Federais - PERT', '8808', NULL),
(140, 'Juros - Cheque Especial', '9701', NULL),
(141, 'Capital de Giro - Itau', '9702', NULL),
(142, 'Capital de Giro - Santander', '9703', NULL),
(143, 'Capital de Giro - CEF', '9704', NULL),
(144, 'Capital de Giro - Bradesco', '9705', NULL);

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `pagar_grupo`
--
ALTER TABLE `pagar_grupo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `pagar_grupo`
--
ALTER TABLE `pagar_grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
