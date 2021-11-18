-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 15/07/2019 às 17:17
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
-- Estrutura para tabela `pagar_conta`
--

CREATE TABLE `pagar_conta` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `previsao` float(9,2) DEFAULT NULL,
  `conta` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `grupo` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `pagar_conta`
--

INSERT INTO `pagar_conta` (`id`, `nome`, `id_grupo`, `previsao`, `conta`, `grupo`) VALUES
(1, 'Clean', NULL, 0.00, NULL, NULL),
(2, 'Padrão', NULL, 0.00, NULL, NULL),
(3, 'Bioclean', NULL, 0.00, NULL, NULL),
(6, 'Edesio', NULL, 0.00, NULL, NULL),
(7, 'Sr Santos', NULL, 0.00, NULL, NULL),
(8, 'Mdakede', NULL, 0.00, NULL, NULL),
(9, 'Hevkede', NULL, 0.00, NULL, NULL),
(10, 'Operacional', NULL, 0.00, NULL, NULL),
(11, 'Administrativo Operacional', NULL, 0.00, NULL, NULL),
(12, 'Portaria / Limpeza', NULL, 0.00, NULL, NULL),
(13, 'Oficina', NULL, 0.00, NULL, NULL),
(14, 'Administrativo', NULL, 0.00, NULL, NULL),
(15, 'Financeiro', NULL, 0.00, NULL, NULL),
(16, 'RH / DP', NULL, 0.00, NULL, NULL),
(17, 'Motorista Infectante', NULL, 0.00, NULL, NULL),
(18, 'Motorista Extraordinario', NULL, 0.00, NULL, NULL),
(19, 'Motorista Extraordinario (Contrato Intermitente)', NULL, 0.00, NULL, NULL),
(20, 'Coletor', NULL, 0.00, NULL, NULL),
(21, 'Coletor (Contrato Intermitente)', NULL, 0.00, NULL, NULL),
(22, 'Comercial', NULL, 0.00, NULL, NULL),
(23, 'Jovem Aprendiz', NULL, 0.00, NULL, NULL);

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `pagar_conta`
--
ALTER TABLE `pagar_conta`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `pagar_conta`
--
ALTER TABLE `pagar_conta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
