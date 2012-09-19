/*
	fmTab - Gerenciador de Abas
	por Dimas Melo Filho
	Possui integração com o Menu e requer o funções do módulo de Menu fmMenu (menu.js).
	Requer o jquery.
	
	** Para fazer: Criar uma versão que não depende do jquery.
*/

fmTabCount = 0;

/**
 * Abre uma nova aba. Ao carregar um módulo em uma nova aba, o carregador de módulos chama a função javascript init_nomedomodulo onde
 * nomedomodulo é o nome do arquivo que gera o módulo. OBS: A extensão do nome do módulo é removida no nome da função. Por exemplo,
 * fmTabNew('geral','cadatroEmpresa.php'); carrega o módulo mGeral/cadastroEmpresa.php e chama a sua função init_cadastroEmpresa();
 * 
 * @author Dimas Melo Filho
 * @param  system A qual subsistema pertence o módulo que será carregado na aba. Pode ser: geral, homecare, hospital, clinca, consultorio.
 * @param  modulo Nome do arquivo (dentro da pasta de subsistema) que gera o código do módulo. 
 */
function fmTabNew(system, module, tabName) {
	fmMenuHide();
	var progAddr = '';
	var systemLower = system.toLowerCase();
	if (systemLower == 'geral') progAddr = 'mGeral/' + module;
	else if (systemLower == 'homecare') progAddr = 'mHomecare/' + module;
	else if (systemLower == 'hospital') progAddr = 'mHospital/' + module;
	else if (systemLower == 'clinica') progAddr = 'mClinica/' + module;
	else if (systemLower == 'consultorio') progAddr = 'mConsultorio/' + module;
	else if (systemLower == 'manual') progAddr = 'mManual/' + module;
	else return false;	
	var currentTab = fmTabCount++;
	progAddr += "?tab=" + currentTab;
	
	/* Escurecer as abas atualmente selecionadas */
	$(".fmTab_ls").removeClass("fmTab_ls").addClass("fmTab_l");
	$(".fmTab_rs").removeClass("fmTab_rs").addClass("fmTab_r");
	$(".fmTab_cs").removeClass("fmTab_cs").addClass("fmTab_c");
	/* Criar uma nova aba como selecionada */
	$("#tabs").append('<div class="fmTab_ls" id="fmTab_l'+currentTab+'"><div class="fmTab_rs" id="fmTab_r'+currentTab+'"><div class="fmTab_cs" id="fmTab_c'+currentTab+'" onclick="fmTabShow('+currentTab+');">'+tabName+'&nbsp;<div class="fmTab_x" id="fmTab_x'+currentTab+'" onclick="fmTabClose('+currentTab+')"></div></div></div></div>');
	/* Criar container da nova aba */
	$("#program").append('<div class="fmTabProg" id="fmTab' + (currentTab) + '"></div>');
	fmTabShow(currentTab);
	$('#fmTab'+currentTab).html("&nbsp;&nbsp;&nbsp;<img src=\"i/ajax-loader.gif\" />&nbsp;&nbsp;Carregando aplica&ccedil;&atilde;o. Por favor, aguarde...");		
	$.ajax(progAddr, {
		error: function(xhr, errType, errText) {
			if (errType == "timeout") {
				$('#fmTab'+currentTab).html("&nbsp;Ocorreu um erro ao tentar carregar a aplica&ccedil;&atilde;o. O tempo limite de espera foi atingido.");
			}
			else if (errType == "parserrror") {
				$('#fmTab'+currentTab).html("&nbsp;Ocorreu um erro ao tentar carregar a aplica&ccedil;&atilde;o. Erro de analisador.");
			}
			else if (errType == "abort") {
				$('#fmTab'+currentTab).html("&nbsp;Ocorreu um erro ao tentar carregar a aplica&ccedil;&atilde;o. A conex&atilde;o foi abortada.");
			}
			else {
				if (errText != null && errText.length > 0) {
					if (errText == 'Not Found') {
						$('#fmTab'+currentTab).html("&nbsp;Ocorreu um erro ao tentar carregar a aplica&ccedil;&atilde;o. O m&oacute;dulo n&atilde;o foi encontrado.");
					} else {
						$('#fmTab'+currentTab).html("&nbsp;Ocorreu um erro desconhecido ao tentar carregar a aplica&ccedil;&atilde;o. A mensagem de erro retornada pelo servidor foi: '" + errText + "'.");
					}
				}
				else {
					$('#fmTab'+currentTab).html("&nbsp;Ocorreu um erro desconhecido ao tentar carregar a aplica&ccedil;&atilde;o. O servidor n&atilde;o informou nenhuma mensagem de erro.");
				}
			}
		},
		success: function (data, succType, xhr) {
			$('#fmTab'+currentTab).html(data);
		}
	});
}

/**
 * Exibe uma aba e oculta as demais.
 *
 * @author Dimas Melo Filho
 * @param  tabId Número da aba que deve ser exibida.
 */
function fmTabShow(tabId) {
	$("#fmTab"+tabId).show();
	$(".fmTabProg:not(#fmTab"+tabId+")").hide();
	$(".fmTab_ls").removeClass("fmTab_ls").addClass("fmTab_l");
	$(".fmTab_rs").removeClass("fmTab_rs").addClass("fmTab_r");
	$(".fmTab_cs").removeClass("fmTab_cs").addClass("fmTab_c");
	$("#fmTab_l"+tabId).removeClass().addClass("fmTab_ls");
	$("#fmTab_r"+tabId).removeClass().addClass("fmTab_rs");
	$("#fmTab_c"+tabId).removeClass().addClass("fmTab_cs");
	def.fieldNavSelectLast(tabId);
}

/**
 * Fecha uma aba e remove seus componentes do formulário.
 *
 * @author Dimas Melo Filho
 * @param  tabId Número da aba que deve ser exibida.
 */
function fmTabClose(tabId) {
	var sel = $("#fmTab_l"+tabId).hasClass('fmTab_ls');
	$("#fmTab"+tabId).remove();
	$("#fmTab_l"+tabId).remove();
	if (sel) setTimeout('fmTabShowLast();',100);
	def.fieldNavRemove(tabId);
}

/**
 * Mostra a última aba da lista.
 *
 * @author Dimas Melo Filho
 */
function fmTabShowLast() {
	var lastTab = $(".fmTabProg").last().attr('id');
	if (lastTab != null) {
		fmTabShow(parseInt(lastTab.substr(5)));
	}
}

/**
 * Passa para a próxima aba.
 *
 * @author Dimas Melo Filho
 */
function fmTabShowNext(event, obj) {
	var nextTab = $(".fmTab_ls").next();
	if (nextTab.length <= 0) nextTab = $(".fmTab_l").first();
	if (nextTab.length <= 0) return false;
	fmTabShow(parseInt(nextTab.attr('id').substr(7)));		
}

/**
 * Passa para a aba anterior.
 *
 * @author Dimas Melo Filho
 */
function fmTabShowPrev(event, obj) {
	var nextTab = $(".fmTab_ls").prev();
	if (nextTab.length <= 0) nextTab = $(".fmTab_l").last();
	if (nextTab.length <= 0) return false;
	fmTabShow(parseInt(nextTab.attr('id').substr(7)));		
}

function fmTabOf(el) {
	while (el != null) {
		if (el.className == 'fmTabProg') return parseInt(el.id.substring(5));
		el = el.parentNode;
	}
	return null;
}

/**
 * Registrar atalhos de teclado
 */
def.addShortcut("alt+q",fmTabShowPrev);
def.addShortcut("alt+w",fmTabShowNext);