-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 26/09/2012 às 10h39min
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

--
-- Extraindo dados da tabela `acao`
--

INSERT INTO `acao` (`codAcao`, `nomeAcao`, `linkAcao`) VALUES
(1, 'Cadastrar', 'cadastrar'),
(2, 'Alterar', 'alterar'),
(3, 'Excluir', 'excluir'),
(4, 'Exibir', 'exibir'),
(5, 'Relatório', 'relatorio'),
(6, 'Alterando', 'alterando');

--
-- Extraindo dados da tabela `aplicacao`
--

INSERT INTO `aplicacao` (`codAplicacao`, `nomeAplicacao`, `linkAplicacao`) VALUES
(1, 'Clínica', 'clinica');

--
-- Extraindo dados da tabela `aplicacao_modulo`
--

INSERT INTO `aplicacao_modulo` (`codAplicacao`, `codModulo`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 46),
(1, 47),
(1, 48),
(1, 49),
(1, 56),
(1, 57),
(1, 58);

--
-- Extraindo dados da tabela `modulo`
--

INSERT INTO `modulo` (`codModulo`, `nomeModulo`, `nivelModulo`, `clickModulo`, `codModuloPai`, `linkModulo`, `visibilidadeModulo`) VALUES
(1, 'Recepção', 0, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', NULL, NULL, NULL),
(2, 'Faturamento', 0, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', NULL, NULL, NULL),
(3, 'Financeiro', 0, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', NULL, NULL, NULL),
(4, 'Prontuário', 0, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', NULL, NULL, NULL),
(5, 'Relatórios', 0, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', NULL, NULL, NULL),
(6, 'Configurações', 0, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', NULL, NULL, NULL),
(7, 'Agendamento', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 1, NULL, NULL),
(8, 'Atendimento', 1, 'fmTabNew(''geral'',''atendimento.php'',''Atendimento'');', 1, 'atendimento', NULL),
(9, 'Paciente', 1, 'fmTabNew(''geral'',''cadastroPaciente.php'',''Cadastro de Pacientes'');', 1, 'paciente', NULL),
(16, 'Faturamento', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(17, 'Remessa', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(18, 'Quitação', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(19, 'Recurso de Glosas', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(20, 'Auditoria', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(21, 'TISS', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(22, 'Orçamento', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(23, 'CIHA', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(24, 'Atualização/consulta de preços', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(25, 'Repasse', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(26, 'Pacotes', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(27, 'NFE', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(28, 'Lançamento', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(29, 'Regras/coberturas', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 2, NULL, NULL),
(30, 'Interna', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 20, NULL, NULL),
(31, 'Externa', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 20, NULL, NULL),
(32, 'Geração do arquivo TISS', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 21, NULL, NULL),
(33, 'Importação de arquivo', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 21, NULL, NULL),
(34, 'Geração do arquivo CIHA', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 23, NULL, NULL),
(35, 'Manual', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 24, NULL, NULL),
(36, 'Automática', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 24, NULL, NULL),
(37, 'Entrada de produtos/serviços', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 3, NULL, NULL),
(38, 'Nota Fiscal', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 3, NULL, NULL),
(39, 'Contas a pagar', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 3, NULL, NULL),
(40, 'Contas a receber', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 3, NULL, NULL),
(41, 'Painel de Atendimento', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 4, NULL, NULL),
(42, 'Atendimento', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 4, NULL, NULL),
(43, 'Recepção', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 5, NULL, NULL),
(44, 'Faturamento', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 5, NULL, NULL),
(45, 'Financeiro', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 5, NULL, NULL),
(46, 'Cadastros', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 6, NULL, NULL),
(47, 'Alertas automáticos', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 6, NULL, NULL),
(48, 'Regras / Coberturas – Geral', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 6, NULL, NULL),
(49, 'Cadastro de PCT', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 6, NULL, NULL),
(50, 'Lista de atendimentos', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 43, NULL, NULL),
(51, 'Gerencial de faturas', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 44, NULL, NULL),
(52, 'Gerencial de remessas', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 44, NULL, NULL),
(53, 'Gerencial de glosas', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 44, NULL, NULL),
(54, 'Mapa de pagamento', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 44, NULL, NULL),
(55, 'Custo por PCT', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 45, NULL, NULL),
(56, 'Repasse', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 45, NULL, NULL),
(57, 'Usuários', 2, 'fmTabNew(''geral'',''cadastroUsuario.php'',''Cadastro de Usu&aacute;rio'');', 46, NULL, NULL),
(58, 'Unidades', 2, 'fmTabNew(''geral'',''cadastroUnidade.php'',''Cadastro de Unidades'');', 46, 'unidades', NULL),
(59, 'Profissionais', 2, 'fmTabNew(''geral'',''cadastroProfissional.php'',''Cadastro de Profissional'');', 46, NULL, NULL),
(60, 'Convênios/Planos', 2, 'fmTabNew(''geral'',''cadastroConvenio.php'',''Conv&ecirc;nios e Planos'');', 46, NULL, NULL),
(61, 'Produtos/Serviços', 2, 'fmTabNew(''geral'',''cadastroItem.php'',''Cadastro de Itens'');', 46, NULL, NULL),
(62, 'Tabelas de Dominios TISS', 2, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 46, NULL, NULL),
(63, 'Alertas automáticos', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 6, NULL, NULL),
(64, 'Regras / Coberturas - Geral', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 6, NULL, NULL),
(65, 'Cadastro de PCT', 1, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 6, NULL, NULL),
(66, 'Tipos de Produtos/serviços', 3, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 61, NULL, NULL),
(67, 'Motivos de Glosas', 3, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', 62, NULL, NULL),
(68, 'Configura&ccedil;&otilde;es Pessoais', 10, 'def.modalBox([''OK''],''Em Desenvolvimento'',''Este m&oacute;dulo est&aacute; em fase de desenvolvimento.'');', NULL, NULL, 1),
(69, 'Sair', 10, 'window.location=''logout.php'';', NULL, 'sair', 1),
(70, 'Trocar Senha', 10, 'fmTabNew(''geral'',''trocarSenha.php'',''Troca de Senha'');', NULL, NULL, 1),
(71, 'Ação', 10, '', NULL, 'acao', NULL),
(72, 'Modulo', 10, '', NULL, 'modulo', NULL),
(73, 'Aplicacao', 10, '', NULL, 'aplicacao', NULL),
(74, 'Perfil', 10, '', NULL, 'perfil', NULL),
(75, 'Usuário', 10, '', NULL, 'usuario', NULL),
(84, 'teste', NULL, '', NULL, 'teste', NULL);

--
-- Extraindo dados da tabela `modulo_acao`
--

INSERT INTO `modulo_acao` (`codModulo`, `codAcao`) VALUES
(7, 1),
(9, 1),
(58, 1),
(84, 1),
(7, 2),
(9, 2),
(58, 2),
(84, 2),
(58, 3),
(84, 3),
(1, 4),
(6, 4),
(46, 4);

--
-- Extraindo dados da tabela `perfil`
--

INSERT INTO `perfil` (`codPerfil`, `nomePerfil`) VALUES
(1, 'Administrador');

--
-- Extraindo dados da tabela `perfil_aplicacao`
--

INSERT INTO `perfil_aplicacao` (`codPerfil`, `codAplicacao`, `codModulo`, `codAcao`) VALUES
(1, 1, 1, 1),
(1, 1, 6, 4),
(1, 1, 7, 1),
(1, 1, 7, 2),
(1, 1, 9, 1),
(1, 1, 9, 2),
(1, 1, 46, 4),
(1, 1, 58, 1),
(1, 1, 58, 2),
(1, 1, 58, 3);

--
-- Extraindo dados da tabela `usuario1`
--

INSERT INTO `usuario1` (`codUsuario`, `codUsuarioCadastrou`, `codPerfil`, `nomeUsuario`, `matriculaUsuario`, `loginUsuario`, `hashSenhaUsuario`, `emailUsuario`, `dtCadastroUsuario`, `dtUltimoLoginUsuario`, `dtBloqueioUsuario`, `motBloqueioUsuario`) VALUES
(1, 0, 1, 'Administrador', NULL, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'victor.ribeiro@jvsolucoes.com.br', '2012-09-10 09:00:00', '2012-09-10 10:00:00', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
