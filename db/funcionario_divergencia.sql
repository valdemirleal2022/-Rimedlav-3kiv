-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 15/07/2019 às 17:36
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
-- Banco de dados: `paozaoam_wpc_sistema`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario_divergencia`
--

CREATE TABLE `funcionario_divergencia` (
  `id` int(11) NOT NULL,
  `id_funcionario` int(11) DEFAULT NULL,
  `id_divergencia` int(11) DEFAULT NULL,
  `status` varchar(35) CHARACTER SET latin1 DEFAULT NULL,
  `data_solicitacao` date DEFAULT NULL,
  `hora_solicitacao` varchar(8) CHARACTER SET latin1 DEFAULT NULL,
  `solicitacao` mediumtext CHARACTER SET latin1,
  `usuario` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `data_solucao` date DEFAULT NULL,
  `hora_solucao` varchar(8) CHARACTER SET latin1 DEFAULT NULL,
  `solucao` mediumtext CHARACTER SET latin1,
  `procedente` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='solicitações de suporte';

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `funcionario_divergencia`
--
ALTER TABLE `funcionario_divergencia`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `funcionario_divergencia`
--
ALTER TABLE `funcionario_divergencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
