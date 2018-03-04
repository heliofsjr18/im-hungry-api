-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 04/03/2018 às 17:55
-- Versão do servidor: 10.1.24-MariaDB-cll-lve
-- Versão do PHP: 5.6.30


--
-- Banco de dados: `I'm hungry`
--

-- --------------------------- Estrutura para Empresas -----------------------------

--
-- Estrutura para tabela `bancos`
--

  CREATE TABLE `bancos` (
    `banco_id` INT(10) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `cod` INT(4) NOT NULL,
    `banco` VARCHAR(150) NOT NULL
  ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `banco_empresa`
--

  CREATE TABLE `banco_empresa` (
    `banco_emp_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `banco_emp_titular_nome` LONGTEXT NOT NULL,
    `banco_emp_titular_tipo` INT(11) NOT NULL,
    `banco_emp_titular_doc` LONGTEXT NOT NULL,
    `banco_emp_agencia` VARCHAR(40) NOT NULL,
    `banco_emp_conta` VARCHAR(40) NOT NULL,
    `banco_emp_flag` TINYINT(1) DEFAULT NULL,
    `banco_id` INT(11) NOT NULL,
    `empresa_id` INT(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `enderecos`
--

CREATE TABLE `enderecos` (
  `id` int(11) NOT NULL,
  `cep` varchar(11) NOT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `cidade` varchar(120) DEFAULT NULL,
  `bairro` varchar(120) DEFAULT NULL,
  `logradouro` varchar(255) DEFAULT NULL,
  `latitude` varchar(30) DEFAULT NULL,
  `longitude` varchar(30) DEFAULT NULL,
  `ibge_cod_uf` varchar(5) DEFAULT NULL,
  `ibge_cod_cidade` varchar(10) DEFAULT NULL,
  `area_cidade_km2` varchar(20) DEFAULT NULL,
  `ddd` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa`
--

  CREATE TABLE `empresa` (
    `empresa_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `empresa_nome` LONGTEXT NOT NULL,
    `empresa_telefone` VARCHAR(11) NOT NULL,
    `empresa_cnpj` VARCHAR(18) NOT NULL,
    `empresa_cep` VARCHAR(9) DEFAULT NULL,
    `empresa_lat` VARCHAR(30) DEFAULT NULL,
    `empresa_long` VARCHAR(30) DEFAULT NULL,
    `empresa_numero_endereco` INT(10) DEFAULT NULL,
    `empresa_complemento_endereco` text NOT NULL,
    `empresa_data_funcacao` DATE NOT NULL,
    `empresa_data_cadastro` DATETIME NOT NULL,
    `empresa_foto_marca` LONGTEXT NOT NULL,
    `empresa_foto_perfil` LONGTEXT NOT NULL,
    `empresa_foto_capa` LONGTEXT NOT NULL,
    `empresa_facebook` LONGTEXT,
    `empresa_instagram` LONGTEXT,
    `empresa_twitter` LONGTEXT,
    `empresa_status` TINYINT(1) DEFAULT NULL,
    `user_id` INT(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


--
-- Estrutura para tabela `cupom_desconto`
--

CREATE TABLE `cupom_desconto` (
  `cupom_id` INT(11) NOT NULL,
  `cupom_desc` LONGTEXT NOT NULL,
  `cupom_validade` DATE NOT NULL,
  `cupom_valor` DOUBLE NOT NULL,
  `cupom_status` TINYINT(1) NOT NULL,
  `empresa_id` INT(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `menu_padrao`
--

CREATE TABLE `menu_padrao` (
  `menu_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `menu_nome` LONGTEXT NOT NULL,
  `menu_status` TINYINT(1) NOT NULL,
  `empresa_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `menu_padrao_itens`
--

CREATE TABLE `menu_padrao_itens` (
  `item_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `item_nome` LONGTEXT NOT NULL,
  `item_valor` DOUBLE NOT NULL,
  `item_tempo_medio` TIME NOT NULL,
  `item_status` TINYINT(1) NOT NULL,
  `item_promocao` TINYINT(1) NOT NULL,
  `menu_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_filial`
--

CREATE TABLE `empresa_filial` (
  `filial_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `filial_nome` LONGTEXT NOT NULL,
  `filial_telefone` VARCHAR(11) NOT NULL,
  `filial_cnpj` VARCHAR(18) NOT NULL,
  `filial_cep` VARCHAR(9) DEFAULT NULL,
  `filial_lat` VARCHAR(30) DEFAULT NULL,
  `filial_long` VARCHAR(30) DEFAULT NULL,
  `filial_numero_endereco` INT(10) DEFAULT NULL,
  `filial_complemento_endereco` LONGTEXT NOT NULL,
  `filial_status` TINYINT(1) DEFAULT NULL,
  `empresa_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `menu_filial`
--

CREATE TABLE `menu_filial` (
  `menu_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `menu_nome` LONGTEXT NOT NULL,
  `menu_status` TINYINT(1) NOT NULL,
  `filial_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `menu_filial_itens`
--

CREATE TABLE `menu_filial_itens` (
  `item_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `item_nome` LONGTEXT NOT NULL,
  `item_valor` DOUBLE NOT NULL,
  `item_tempo_medio` TIME NOT NULL,
  `item_status` TINYINT(1) NOT NULL,
  `item_promocao` TINYINT(1) NOT NULL,
  `menu_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_fotos`
--

CREATE TABLE `itens_fotos` (
  `fot_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `fot_file` LONGTEXT NOT NULL,
  `item_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- --------------------------- Estrutura para Clientes -----------------------------

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_usuario`
--

CREATE TABLE `tipo_usuario` (
    `tipo_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `tipo_desc` LONGTEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

  CREATE TABLE `usuarios` (
    `user_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `user_nome` LONGTEXT NOT NULL,
    `user_cpf` VARCHAR(11) NOT NULL,
    `user_email` LONGTEXT NOT NULL,
    `user_senha` VARCHAR(40) NOT NULL,
    `user_telefone` VARCHAR(11) NOT NULL,
    `user_data` DATE NOT NULL,
    `user_cadastro` DATETIME NOT NULL,
    `user_foto_perfil` LONGTEXT DEFAULT NULL,
    `user_status` TINYINT(1) NOT NULL,
    `tipo_id`INT(1) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_cartao`
--

  CREATE TABLE `clientes_cartao` (
    `cartao_id` INT(11) NOT NULL,
    `cartao_digitos` VARCHAR(4) NOT NULL,
    `cartao_brand` VARCHAR(20) NOT NULL,
    `cartao_token` LONGTEXT NOT NULL,
    `cartao_status` TINYINT(1) NOT NULL,
    `user_id` INT(11) NOT NULL
  ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `checkout`
--

  CREATE TABLE `checkout` (
    `checkout_id` INT(11) NOT NULL,
    `checkout_ref` VARCHAR(50) NOT NULL,
    `checkout_code` VARCHAR(40) DEFAULT NULL,
    `checkout_status` INT(11) NOT NULL,
    `checkout_date` DATETIME NOT NULL,
    `checkout_last_event` DATETIME NOT NULL,
    `checkout_valor_bruto` DOUBLE NOT NULL,
    `checkout_valor_liquido` DOUBLE NOT NULL,
    `checkout_forma_pagamento` INT(1) DEFAULT NULL,
    `user_id` INT(11) NOT NULL,
    `cupom_id` INT(11) NOT NULL,
    `cartao_id` INT(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `checkout_itens`
--

  CREATE TABLE `checkout_itens` (
    `checkout_item_id` INT(11) NOT NULL,
    `checkout_item_qtd` INT(11) NOT NULL,
    `checkout_item_valor` DOUBLE NOT NULL,
    `item_id` INT(11) NOT NULL,
    `checkout_id` INT(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;