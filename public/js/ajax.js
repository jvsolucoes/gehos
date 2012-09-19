function fmAjax(url, dados, sucesso) {
	return $.ajax(url,
		{
			error: fmAjaxError,
			success: function (data) { if (!fmAjaxErrorMessage(data)) sucesso(data); },
			data: dados,
			method: 'POST'
		}
	);
}

function fmAjaxErrorMessage(data) {
	if (data.charAt(0) == "#") {	// Erro Versão 1
		var erro = data.substr(1).split(':>>>',2);
		if (erro.length >= 2 && erro[0].length > 0) {
			def.modalBox(
				['OK','Ajuda'],
				"Erro", 
				erro[1], 
				function(b) { if (b == 'Ajuda') fmTabNew('manual',"mGeral/manual.php?page="+erro[0],"Ajuda do Gehos"); }
			);
		} else def.modalBox(['OK'],"Erro",data,null);
		return true;
	}
	return false;
}

function fmAjaxError(xhr, errType, errText) {
	if (errType == "timeout") {
		def.modalBox(['OK','Ajuda'],"Erro de Conex&atilde;o","Ocorreu um erro com a conex&atilde;o do servidor. O tempo de conex&atilde;o expirou e o servidor n&atilde;o respondeu.", this.clickErrorTimeout);
	}
	else if (errType == "parsererror") {
		def.modalBox(['OK','Ajuda'],"Erro de Conex&atilde;o","Ocorreu um erro com a conex&atilde;o do servidor. Os dados retornados são inv&aacute;lidos.", this.clickErrorParserError);
	} // O erro "abort" não é tratado pois não é um erro e sim um aviso de que o usuário cancelou a conexão de propósito.
	if (errType != "abort") {
		if (errText != null && errText.length > 0) {
			if (errText == 'Not Found') {
				def.modalBox(['OK','Ajuda'],"Erro de Conex&atilde;o","Ocorreu um erro com a conex&atilde;o do servidor. O arquivo do módulo n&atilde;o foi encontrado.", this.clickErrorNotFound);
			} else {
				def.modalBox(['OK','Ajuda'],"Erro de Conex&atilde;o","Ocorreu um erro desconhecido ao tentar a conex&atilde;o com o servidor. O texto retornado pelo servidor foi: "+ errText + ". Detalhes: '" + xhr.responseText + "'.", this.clickErrorUnknownMessage);
			}
		}
		else {
			def.modalBox(['OK','Ajuda'],"Erro de Conex&atilde;o","Ocorreu um erro desconhecido ao tentar a conex&atilde;o com o servidor. O servidor n&atilde;o informou nenhum c&oacute;digo de erro.", this.clickErrorNotUnknown);
		}
	}
}