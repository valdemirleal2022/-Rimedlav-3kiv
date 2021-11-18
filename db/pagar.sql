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
-- Estrutura para tabela `pagar`
--

CREATE TABLE `pagar` (
  `id` int(11) NOT NULL,
  `id_conta` int(11) DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `emissao` date DEFAULT NULL,
  `vencimento` date DEFAULT NULL,
  `programacao` date DEFAULT NULL,
  `pagamento` date DEFAULT NULL,
  `valor` float(9,2) DEFAULT NULL,
  `juros` float(9,2) DEFAULT NULL,
  `desconto` float(9,2) DEFAULT NULL,
  `formpag` int(11) DEFAULT NULL,
  `banco` int(11) DEFAULT NULL,
  `descricao` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parcela` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nota` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` int(1) DEFAULT NULL,
  `conta` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interacao` timestamp NULL DEFAULT NULL,
  `usuario` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `empresa` int(11) DEFAULT NULL,
  `fornecedor` int(11) DEFAULT NULL,
  `funcionario` int(11) DEFAULT NULL,
  `codigo_barra` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `pagar`
--

INSERT INTO `pagar` (`id`, `id_conta`, `id_grupo`, `emissao`, `vencimento`, `programacao`, `pagamento`, `valor`, `juros`, `desconto`, `formpag`, `banco`, `descricao`, `parcela`, `cheque`, `nota`, `status`, `tipo`, `conta`, `numero`, `interacao`, `usuario`, `empresa`, `fornecedor`, `funcionario`, `codigo_barra`) VALUES
(12, 14, 58, '2019-06-13', '2019-06-13', NULL, '0000-00-00', 120.00, NULL, NULL, 1, 8, 'teste', '1/2', '', '', 'Em Aberto', NULL, NULL, NULL, '2019-07-15 14:22:09', 'Wellington Pessoa', 3, 17, 329, ''),
(13, 5, NULL, '2019-06-20', '2019-06-20', NULL, NULL, 150.00, NULL, NULL, 1, 1, 'MERCPAGO', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-20 15:26:02', NULL, 1, 17, 278, ''),
(14, 35, NULL, '2019-06-13', '2019-06-13', NULL, '0000-00-00', 560.00, NULL, NULL, 1, 1, 'MERCPAGO', '', '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-20 15:34:08', 'Wellington Pessoa', 2, 22, 278, ''),
(15, 11, NULL, '2019-06-23', '2019-06-23', '2019-06-23', NULL, 2.00, NULL, NULL, 2, 1, 'TAR/CUSTAS COBRANCA', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-23 23:55:07', NULL, 0, 0, 0, ''),
(16, 11, NULL, '2019-06-24', '2019-06-24', NULL, NULL, 2.00, NULL, NULL, 0, 0, 'teste', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 14:40:28', NULL, NULL, NULL, NULL, ''),
(17, 11, NULL, '2019-06-24', '2019-06-24', NULL, NULL, 3.00, NULL, NULL, 0, 0, 'TAR/CUSTAS COBRANCA', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 14:42:58', NULL, NULL, NULL, NULL, ''),
(18, 11, NULL, '2019-06-24', '2019-06-24', NULL, NULL, 3.00, NULL, NULL, 0, 0, 'TAR/CUSTAS COBRANCA', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 14:45:18', NULL, NULL, NULL, NULL, ''),
(19, 11, NULL, '2019-06-24', '2019-06-24', NULL, NULL, 3.00, NULL, NULL, 0, 0, 'TAR/CUSTAS COBRANCA', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 14:46:09', NULL, NULL, NULL, NULL, ''),
(20, 11, NULL, '2019-06-24', '2019-06-24', NULL, NULL, 3.00, NULL, NULL, 0, 0, 'TAR/CUSTAS COBRANCA', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 14:48:11', NULL, NULL, NULL, NULL, ''),
(21, 11, NULL, '2019-06-24', '2019-06-24', NULL, NULL, 3.00, NULL, NULL, 0, 0, 'TAR/CUSTAS COBRANCA', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 14:48:59', NULL, NULL, NULL, NULL, ''),
(22, 11, NULL, '2019-06-24', '2019-06-24', NULL, NULL, 4.00, NULL, NULL, 0, 0, 'teeste', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 14:56:08', NULL, NULL, NULL, NULL, ''),
(23, 11, NULL, '2019-06-24', '2019-06-24', NULL, NULL, 4.00, NULL, NULL, 0, 0, 'teeste', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 14:57:59', NULL, NULL, NULL, NULL, ''),
(24, 11, NULL, '2019-06-24', '2019-06-24', NULL, NULL, 4.00, NULL, NULL, 0, 0, 'teeste', '', '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 15:01:23', NULL, NULL, NULL, NULL, ''),
(25, 11, NULL, '2019-06-24', '2019-06-24', NULL, NULL, 5.00, NULL, NULL, 0, 0, 'teste', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 15:08:40', NULL, NULL, NULL, NULL, ''),
(26, 11, NULL, '2019-06-24', '2019-06-24', NULL, NULL, 5.00, NULL, NULL, 0, 0, 'teste', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 15:10:51', NULL, NULL, NULL, NULL, ''),
(27, 11, NULL, '2019-06-24', '2019-06-24', NULL, NULL, 5.00, NULL, NULL, 0, 0, 'teste', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 15:14:02', NULL, NULL, NULL, NULL, ''),
(28, 11, 20, '2019-06-24', '2019-06-24', NULL, NULL, 6.00, NULL, NULL, 0, 0, 'google', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 15:20:42', NULL, NULL, NULL, NULL, ''),
(29, 11, 20, '2019-06-24', '2019-06-24', NULL, NULL, 6.00, NULL, NULL, 0, 0, 'google', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 15:23:29', NULL, NULL, NULL, NULL, ''),
(30, 11, 95, '2019-06-24', '2019-06-24', NULL, NULL, 7.00, NULL, NULL, 0, 0, 'TAR/CUSTAS COBRANCA', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 15:26:51', NULL, NULL, NULL, NULL, ''),
(31, 11, 95, '2019-06-24', '2019-06-24', NULL, NULL, 7.00, NULL, NULL, 0, 0, 'TAR/CUSTAS COBRANCA', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 15:35:28', NULL, NULL, NULL, NULL, ''),
(32, 11, 95, '2019-06-24', '2019-06-24', NULL, NULL, 7.00, NULL, NULL, 0, 0, 'TAR/CUSTAS COBRANCA', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 15:37:00', NULL, NULL, NULL, NULL, ''),
(33, 11, 95, '2019-06-24', '2019-06-24', NULL, NULL, 8.00, NULL, NULL, 0, 0, 'teste', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 15:39:12', NULL, NULL, NULL, NULL, ''),
(34, 11, 95, '2019-06-24', '2019-06-24', NULL, NULL, 8.00, NULL, NULL, 0, 0, 'teste', NULL, '', '', 'Em Aberto', NULL, NULL, NULL, '2019-06-24 15:43:39', NULL, NULL, NULL, NULL, '');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `pagar`
--
ALTER TABLE `pagar`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `pagar`
--
ALTER TABLE `pagar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
