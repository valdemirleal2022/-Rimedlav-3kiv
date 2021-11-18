-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 15/07/2019 às 17:24
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
-- Estrutura para tabela `empresa_pagar`
--

CREATE TABLE `empresa_pagar` (
  `nome` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `endereco` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bairro` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cidade` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cep` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uf` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `celular` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pais` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnpj` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inscricao` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL,
  `ordem_servico` int(11) DEFAULT NULL,
  `responsavel` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cargo` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inea` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inmetro` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_servico_federal` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_servico_municipal` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iss` float(9,2) DEFAULT NULL,
  `optante_simples` int(1) DEFAULT NULL,
  `nota_descricao` mediumtext COLLATE utf8_unicode_ci,
  `contrato` longtext COLLATE utf8_unicode_ci,
  `sobre` mediumtext COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `empresa_pagar`
--

INSERT INTO `empresa_pagar` (`nome`, `endereco`, `bairro`, `cidade`, `cep`, `uf`, `telefone`, `celular`, `pais`, `cnpj`, `inscricao`, `id`, `ordem_servico`, `responsavel`, `cargo`, `inea`, `inmetro`, `email`, `codigo_servico_federal`, `codigo_servico_municipal`, `iss`, `optante_simples`, `nota_descricao`, `contrato`, `sobre`) VALUES
('Clean 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'wewe', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CLEAN 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '16.478.942/0001-10', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('A CLEAN AMBIENTAL SERVIÇOS DE COLETA E TRANSPORTES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '05539814000112', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('Padrão Ambiental Coleta e Transportes Eireli', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '13.647.096/0001-26', NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `empresa_pagar`
--
ALTER TABLE `empresa_pagar`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `empresa_pagar`
--
ALTER TABLE `empresa_pagar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
