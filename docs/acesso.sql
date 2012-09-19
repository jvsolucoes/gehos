-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 10/09/2012 às 10h06min
-- Versão do Servidor: 5.5.24
-- Versão do PHP: 5.4.6-2~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `gehos`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `acao`
--

DROP TABLE IF EXISTS `acao`;
CREATE TABLE IF NOT EXISTS `acao` (
  `codAcao` int(11) NOT NULL AUTO_INCREMENT,
  `nomeAcao` varchar(45) NOT NULL,
  `linkAcao` varchar(45) NOT NULL,
  PRIMARY KEY (`codAcao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `aplicacao`
--

DROP TABLE IF EXISTS `aplicacao`;
CREATE TABLE IF NOT EXISTS `aplicacao` (
  `codAplicacao` int(11) NOT NULL AUTO_INCREMENT,
  `nomeAplicacao` varchar(45) NOT NULL,
  `linkAplicacao` varchar(45) NOT NULL,
  PRIMARY KEY (`codAplicacao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `aplicacao_modulo`
--

DROP TABLE IF EXISTS `aplicacao_modulo`;
CREATE TABLE IF NOT EXISTS `aplicacao_modulo` (
  `codAplicacao` int(11) NOT NULL,
  `codModulo` int(11) NOT NULL,
  PRIMARY KEY (`codAplicacao`,`codModulo`),
  KEY `fk_aplicacao_modulo_codModulo` (`codModulo`),
  KEY `fk_aplicacao_modulo_codAplicacao` (`codAplicacao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `modulo`
--

DROP TABLE IF EXISTS `modulo`;
CREATE TABLE IF NOT EXISTS `modulo` (
  `codModulo` int(11) NOT NULL AUTO_INCREMENT,
  `nomeModulo` varchar(45) NOT NULL,
  `linkModulo` varchar(45) NOT NULL,
  `menu` tinyint(4) NOT NULL,
  `codMenuPai` int(11) DEFAULT NULL,
  PRIMARY KEY (`codModulo`),
  KEY `fk_modulo_codMenuPai` (`codMenuPai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `modulo_acao`
--

DROP TABLE IF EXISTS `modulo_acao`;
CREATE TABLE IF NOT EXISTS `modulo_acao` (
  `codModulo` int(11) NOT NULL,
  `codAcao` int(11) NOT NULL,
  PRIMARY KEY (`codModulo`,`codAcao`),
  KEY `fk_modulo_acao_codAcao` (`codAcao`),
  KEY `fk_modulo_acao_codModulo` (`codModulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfil`
--

DROP TABLE IF EXISTS `perfil`;
CREATE TABLE IF NOT EXISTS `perfil` (
  `codPerfil` int(11) NOT NULL AUTO_INCREMENT,
  `nomePerfil` varchar(100) NOT NULL,
  PRIMARY KEY (`codPerfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfil_aplicacao`
--

DROP TABLE IF EXISTS `perfil_aplicacao`;
CREATE TABLE IF NOT EXISTS `perfil_aplicacao` (
  `codPerfil` int(11) NOT NULL,
  `codAplicacao` int(11) NOT NULL,
  `codModulo` int(11) NOT NULL,
  `codAcao` int(11) NOT NULL,
  PRIMARY KEY (`codPerfil`,`codAplicacao`,`codModulo`,`codAcao`),
  KEY `fk_perfil_aplicacao_codAplicacao` (`codAplicacao`),
  KEY `fk_perfil_aplicacao_codPerfil` (`codPerfil`),
  KEY `fk_perfil_aplicacao_codModulo` (`codModulo`),
  KEY `fk_perfil_aplicacao_codAcao` (`codAcao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `codUsuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codGrupoUsuario` int(10) unsigned NOT NULL,
  `codUsuarioCadastrou` int(10) unsigned NOT NULL,
  `nomeUsuario` varchar(200) DEFAULT NULL,
  `matriculaUsuario` varchar(20) DEFAULT NULL,
  `loginUsuario` varchar(20) NOT NULL,
  `hashSenhaUsuario` varchar(64) NOT NULL DEFAULT '',
  `emailUsuario` varchar(255) DEFAULT NULL,
  `dtCadastroUsuario` datetime NOT NULL,
  `dtUltimoLoginUsuario` datetime NOT NULL,
  `dtBloqueioUsuario` datetime DEFAULT NULL,
  `motBloqueioUsuario` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`codUsuario`),
  KEY `Usuario_FKIndex1` (`codGrupoUsuario`),
  KEY `Usuario_FKIndex2` (`codUsuarioCadastrou`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`codUsuario`, `codGrupoUsuario`, `codUsuarioCadastrou`, `nomeUsuario`, `matriculaUsuario`, `loginUsuario`, `hashSenhaUsuario`, `emailUsuario`, `dtCadastroUsuario`, `dtUltimoLoginUsuario`, `dtBloqueioUsuario`, `motBloqueioUsuario`) VALUES
(1, 1, 1, NULL, NULL, 'dimas', 'dbfebbcb3b2f2dc003635a6af82705349ebe4f7cc584a1b249824525c16a9567', 'dmelofilho@fmarquesconsultoria.com.br', '2012-05-15 16:25:00', '2012-05-15 16:25:00', NULL, NULL),
(2, 1, 1, NULL, NULL, 'matheus', 'cbbb28f8eeb24603e6f4325b5f734597659dfef73e3223d3467add31f952e9b3', 'matheus.santos@fmarquesconsultoria.com.br', '2012-05-15 16:25:00', '2012-05-15 16:25:00', NULL, ''),
(3, 1, 1, NULL, NULL, 'fernando', '449795f7fb55a544bb316a575159db72b4144cf6997a4879644c16758f5554d8', 'fernandomarques@fmarquesconsultoria.com.br', '2012-05-15 16:25:00', '2012-05-25 12:25:00', NULL, NULL),
(4, 2, 2, NULL, NULL, 'artur', '00390de2b7074071bb6494e818e84884ef6331ceb0b1e70948bde3ef4ba57b92', 'artur.ugiette@fmarquesconsultoria.com.br', '2012-05-15 16:25:00', '2012-05-15 16:25:00', NULL, NULL),
(5, 1, 2, 'Usuario Teste', '123124', 'teste', '481f6cc0511143ccdd7e2d1b1b94faf0a700a8b49cd13922a70b5ae28acaa8c5', 'teste@teste.com.br', '2012-06-29 11:21:49', '2012-06-29 11:21:49', NULL, NULL),
(6, 1, 2, 'Lucas Ugiette', '', 'lucas', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'lucas.ugiette@fmarquesconsultoria.com.br', '2012-07-18 12:32:58', '2012-07-18 12:32:58', NULL, NULL);

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `aplicacao_modulo`
--
ALTER TABLE `aplicacao_modulo`
  ADD CONSTRAINT `fk_aplicacao_modulo_codAplicacao` FOREIGN KEY (`codAplicacao`) REFERENCES `aplicacao` (`codAplicacao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_aplicacao_modulo_codModulo` FOREIGN KEY (`codModulo`) REFERENCES `modulo` (`codModulo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `modulo`
--
ALTER TABLE `modulo`
  ADD CONSTRAINT `fk_modulo_codMenuPai` FOREIGN KEY (`codMenuPai`) REFERENCES `modulo` (`codModulo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `modulo_acao`
--
ALTER TABLE `modulo_acao`
  ADD CONSTRAINT `fk_modulo_acao_codModulo` FOREIGN KEY (`codModulo`) REFERENCES `modulo` (`codModulo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_modulo_acao_codAcao` FOREIGN KEY (`codAcao`) REFERENCES `acao` (`codAcao`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `perfil_aplicacao`
--
ALTER TABLE `perfil_aplicacao`
  ADD CONSTRAINT `fk_perfil_aplicacao_codPerfil` FOREIGN KEY (`codPerfil`) REFERENCES `perfil` (`codPerfil`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_perfil_aplicacao_codAplicacao` FOREIGN KEY (`codAplicacao`) REFERENCES `aplicacao` (`codAplicacao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_perfil_aplicacao_codModulo` FOREIGN KEY (`codModulo`) REFERENCES `modulo` (`codModulo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_perfil_aplicacao_codAcao` FOREIGN KEY (`codAcao`) REFERENCES `acao` (`codAcao`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`codGrupoUsuario`) REFERENCES `grupousuario` (`codGrupoUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`codUsuarioCadastrou`) REFERENCES `usuario` (`codUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
