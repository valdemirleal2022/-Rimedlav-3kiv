-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 26/10/2019 às 18:03
-- Versão do servidor: 5.7.28
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
-- Banco de dados: `fielrj_wpc_sistema`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente_cobranca`
--

CREATE TABLE `cliente_cobranca` (
  `id` int(11) NOT NULL,
  `id_cliente` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` int(1) NOT NULL,
  `nome` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `endereco` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `complemento` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cep` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bairro` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cidade` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uf` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `referencia` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnpj` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cpf` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inscricao` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `celular` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contato` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `senha` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_financeiro` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `classificacao` int(11) DEFAULT NULL,
  `observacao` longtext COLLATE utf8_unicode_ci,
  `data` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `restricao` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nao_enviar_email` int(1) DEFAULT NULL,
  `interacao` timestamp NULL DEFAULT NULL,
  `ultimo_vencimento` date DEFAULT NULL,
  `orcamento_data` date DEFAULT NULL,
  `garagem` int(1) DEFAULT NULL,
  `etapa_limpeza` int(2) DEFAULT NULL,
  `zoneamento` int(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='clientes do sistema';

--
-- Despejando dados para a tabela `cliente_cobranca`
--

INSERT INTO `cliente_cobranca` (`id`, `id_cliente`, `tipo`, `nome`, `endereco`, `numero`, `complemento`, `cep`, `bairro`, `cidade`, `uf`, `referencia`, `cnpj`, `cpf`, `inscricao`, `telefone`, `celular`, `contato`, `email`, `senha`, `email_financeiro`, `classificacao`, `observacao`, `data`, `status`, `restricao`, `latitude`, `longitude`, `nao_enviar_email`, `interacao`, `ultimo_vencimento`, `orcamento_data`, `garagem`, `etapa_limpeza`, `zoneamento`) VALUES
(1, '15946', 0, 'Luzia Silveste Vital', 'Rua Comandante Coimbra', '80', '202 fundos', '21073-040', 'Olaria', 'Rio de Janeiro', 'RJ', '', '', '931.299.207-49', '', '', '', '', '16117@empresa.com.br', '', '16117@empresa.com.br', 0, '', '0000-00-00 00:00:00', 0, '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00', '0000-00-00', 0, 0, 0),
(16, '1027', 0, 'AMADEU ABILHO MACHADO', 'Rua Leopoldina Rego', '260', 'APT. 302', '21021-520', 'Ramos', 'Rio de Janeiro', 'RJ', '', '', '599.445.607-15', '', '', '', '', '', '', '', 0, '', '0000-00-00 00:00:00', 0, '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00', '0000-00-00', 0, 0, 0),
(12, '16128', 0, 'GRUPO CITY SERVICE', 'Rua Jardim Botânico', '1008', '', '22460-000', 'Jardim Botânico', 'Rio de Janeiro', 'RJ', NULL, '08.219.617/0001-04', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, '11829', 0, 'FELIPE MENDONÇA DE ARAUJO', 'Rua Professor Viana da Silva', '510', 'AP 202', '21235-740', 'Braz de Pina', 'Rio de Janeiro', 'RJ', NULL, '', '08907954798', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, '6517', 0, 'RONALDO RODRIGUES', 'Rua Gilda de Abreu', '103', '', '20251490', 'Catumbi', 'Rio de Janeiro', 'RJ', NULL, '', '934.308.767-53', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, '6602', 0, 'RONALDO RODRIGUES', 'Rua Gilda de Abreu', '103', 'BL 02', '20251490', 'Catumbi', 'Rio de Janeiro', 'RJ', NULL, '', '934.308.767-53', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, '16115', 0, 'SILVIA DOS SANTOS SOUZA', '', '', '', '21235-570', '', '', '', '', '', '54742366700', '', '', '', '', '', '', '', 0, '', '0000-00-00 00:00:00', 0, '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00', '0000-00-00', 0, 0, 0),
(18, '5344', 0, 'ROSEMARY MACHADO PASCHOAL', 'Rua Gregório de Castro Morais', '662', 'apt. 201', '21931-350', 'Jardim Guanabara', 'Rio de Janeiro', 'RJ', NULL, '', '484.425.777-34', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, '4918', 0, 'RAFAEL BESTEFANO FERNANDES', 'Rua Carlina', '34', 'APT 103', '21021-360', 'Olaria', 'Rio de Janeiro', 'RJ', NULL, '', '101.537.287.28', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, '15412', 0, 'CRISTINA SABINO ERBACHER', 'Rua Venâncio Veloso', '57', '', '22790-420', 'Recreio dos Bandeirantes', 'Rio de Janeiro', 'RJ', NULL, '', '59779373772', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, '439', 0, 'SRA. FATIMA FERNANDES', 'Rua Japeri', '40', 'APT. 302', '20.261-08', 'Rio Comprido', 'Rio de Janeiro', 'RJ', NULL, '', '025.499.177-74', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, '14919', 0, 'SUPER MERCADO ZONA SUL S/A', 'Rua Comandante Vergueiro da Cruz', '226', '', '21021020', 'Olaria', 'Rio de Janeiro', 'RJ', NULL, '33.381.286/0001-51', '', '77.178.996', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, '14912', 0, 'SUPER MERCADO ZONA SUL S/A', 'Rua Comandante Vergueiro da Cruz', '226', '', '21021020', 'Olaria', 'Rio de Janeiro', 'RJ', NULL, '33.381.286/0001-51', '', '77.178.996', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, '15430', 0, 'SUPER MERCADO ZONA SUL S/A', 'Rua Comandante Vergueiro da Cruz', '226', '', '21021020', 'Olaria', 'Rio de Janeiro', 'RJ', NULL, '33.381.286/0001-51', '', '77.178.996', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, '14910', 0, 'SUPER MERCADO ZONA SUL S/A', 'Rua Comandante Vergueiro da Cruz', '226', '', '21021020', 'Olaria', 'Rio de Janeiro', 'RJ', NULL, '33.381.286/0001-51', '', '77.178.996', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, '14910', 0, 'SUPER MERCADO ZONA SUL S/A', 'Rua Comandante Vergueiro da Cruz', '226', '', '21021020', 'Olaria', 'Rio de Janeiro', 'RJ', NULL, '33.381.286/0001-51', '', '77.178.996', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, '16146', 0, 'LQ292 EMPREENDIMENTO IMOBILIARIO LTDA', 'Rua do Parque', '31', 'parte', '20940050', 'São Cristóvão', 'Rio de Janeiro', 'RJ', NULL, '28891150000151', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, '14911', 0, 'SUPER MERCADO ZONA SUL S/A', 'Rua Comandante Vergueiro da Cruz', '226', '', '21021020', 'Olaria', 'Rio de Janeiro', 'RJ', NULL, '33.381.286/0001-51', '', '77.178.996', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, '11128', 0, 'ELIANE', 'Avenida Dom Hélder Câmara', '1475', 'Ap 302', '20973-011', 'Benfica', 'Rio de Janeiro', 'RJ', NULL, '', '34810528715', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, '15593', 0, 'RCS TECNOLOGIA LTDA', 'Quadra SAAN Quadra 3', 'Q 03 / LOT', '', '70632-310', 'Zona Industrial', 'Brasília', 'DF', '', '08.220.952/0001-22', '', '', '', '', '', '', '', '', 0, '', '0000-00-00 00:00:00', 0, '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00', '0000-00-00', 0, 0, 0),
(31, '16210', 0, 'SUPER MERCADO ZONA SUL S/A', 'Rua Comandante Vergueiro da Cruz', '226', '', '21021020', 'Olaria', 'Rio de Janeiro', 'RJ', NULL, '33.381.286/0001-51', '', '77.178.996', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, '12184', 0, 'SANDRA CARVALHO ABADE', 'Rua Oman', '45', '', '22620-190', 'Barra da Tijuca', 'Rio de Janeiro', 'RJ', '', '', '37516884715', '', '', '', '', '', '', '', 0, '', '0000-00-00 00:00:00', 0, '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00', '0000-00-00', 0, 0, 0),
(33, '12074', 0, 'ALFREDO FIGUEIREDO RIBEIRO', 'Avenida Genaro de Carvalho', '2.900', '', '22795-078', 'Recreio dos Bandeirantes', 'Rio de Janeiro', 'RJ', NULL, '', '382.770.937-72', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, '16214', 0, 'NADIR DA SILVA GARCIA', 'Rua Honduras', '211', '', '26215-450', 'Metrópole', 'Nova Iguaçu', 'RJ', '', '', '09405445715', '', '', '', '', '', '', '', 0, '', '0000-00-00 00:00:00', 0, '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00', '0000-00-00', 0, 0, 0),
(35, '16215', 0, 'Beatriz Moreira Casotti', '', '', '', '22470070', '', '', '', NULL, '', '971. 277.107-59', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, '16215', 0, 'Beatriz Moreira Casotti', '', '', '', '22470070', '', '', '', NULL, '', '971. 277.107-59', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, '1803', 0, 'ANNE DE SOUZA DIAS', 'Avenida Epitácio Pessoa', '332', '', '22410-090', 'Ipanema', 'Rio de Janeiro', 'RJ', NULL, '', '90193296772', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, '15510', 0, 'UNITEC UNIDADE DE IMUNIZAÇÃO TEC E CONSERVAÇÃO', 'Rua Borneo', '400', '', '21350-150', 'Madureira', 'Rio de Janeiro', 'RJ', '', '68.605.641/0001-03', '', '', '', '', '', '', '', '', 0, '', '0000-00-00 00:00:00', 0, '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00', '0000-00-00', 0, 0, 0),
(39, '15890', 0, 'GB CONSULTORIA E SERV  LTDA(CFAP)', 'Avenida das Américas', '12900', '', '22790-702', 'Recreio dos Bandeirantes', 'Rio de Janeiro', 'RJ', NULL, '28.176.998/0004-41', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, '4964', 0, 'VALTER JOAQUIM', 'Rua Comandante Vergueiro da Cruz', '41', 'apt 201', '21021-020', 'Olaria', 'Rio de Janeiro', 'RJ', NULL, '', '018.943.317-53', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, '8913', 0, 'LUCIA DA SILVA ANDRADE', 'Rua Garibaldi', '140', '', '20511-330', 'Tijuca', 'Rio de Janeiro', 'RJ', NULL, '', '40881350753', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, '13306', 0, 'CYRELA RJZ JC GONTIJO EMPREENDIMENTOS IMOBILIÁRIOS LTDA', 'Avenida das Américas', '2480', 'SALA 308', '22640102', 'Barra da Tijuca', 'Rio de Janeiro', 'RJ', NULL, '09465200000275', '', '460.741-4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, '16258', 0, 'MARCIO CAVALCANTE DA SILVA', 'Rua Doutor Antônio Bento', '560', 'CJ 1107', '04750-001', 'Santo Amaro', 'São Paulo', 'SP', NULL, '69137768000108', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, '16267', 0, 'QUALISERVICE CONSERVADORA E SERVIÇOS LTDA', 'Estrada do Engenho da Pedra', '1209', 'B', '21031-485', 'Olaria', 'Rio de Janeiro', 'RJ', '', '09134653000138', '', '', '', '', '', '', '', '', 0, '', '0000-00-00 00:00:00', 0, '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00', '0000-00-00', 0, 0, 0),
(45, '16268', 0, 'QUALISERVICE CONSERVADORA E SERVIÇOS LTDA', 'Estrada do Engenho da Pedra', '1209', 'B', '21031-485', 'Olaria', 'Rio de Janeiro', 'RJ', NULL, '09.134.653/0001-38', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, '5940', 0, 'FERNANDA FREITAS LEITÃO ( TABELIÃ)', 'Rua do Ouvidor', '89', '', '20040-030', 'Centro', 'Rio de Janeiro', 'RJ', NULL, '', '96106441715', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, '5940', 0, 'FERNANDA DE FREITAS LEITÃO', 'Rua do Ouvidor', '89', '', '20040-030', 'Centro', 'Rio de Janeiro', 'RJ', NULL, '', '961.064.417-15', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, '8105', 0, 'WANDA RACHEL ROSSI GUIMARAES.', 'Rua Hermenegildo de Barros', '38', '', '20241-040', 'Santa Teresa', 'Rio de Janeiro', 'RJ', NULL, '', '18.167.080/706 -', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, '16332', 0, 'JOSELIA MATOSO LINS GOUVEIA', 'Rua Marechal Francisco de Moura', '58', 'AT 201', '22260-140', 'Botafogo', 'Rio de Janeiro', 'RJ', NULL, '', '550.243.607-10', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `cliente_cobranca`
--
ALTER TABLE `cliente_cobranca`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `cliente_cobranca`
--
ALTER TABLE `cliente_cobranca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
