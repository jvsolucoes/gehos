/*
 * Classe para automação de cadastro.
 */
 
/**
 * Construtor da classe fmCadastro para automação de cadastro.
 * Ela recebe dois parâmetros, um deles é o nome do arquivo ajax. O outro é uma lista de campos com suas respectivas
 * propriedades.
 * Cada campo deve conter as seguintes propriedades:
 * - id: id do elemento no formulário. (obrigatório)
 * - name: nome do elemento para funções POST. (O nome é preenchido automaticamente pelo nome do elemento input, este parâmetro só deve ser usado caso o usuário deseje substituir o nome original do elemento.)
 * - nameEx: Nome para exibição (pode conter espaços).
 * minChars: quantidade mínima de caracteres ou 0 para ignorar a quantidade mínima.
 * maxChars: quantidade máxima de caracteres ou 0 para infinito.
 * regex: Expressão regular para validação (quando necessário).
 * validation: Lista com funções extras de validação (quando necessário), cada função recebe como parâmetro o texto do campo e deve retornar true quando válido ou uma string com a mensagem de erro quando inválido.
 * errorMin: Erro a ser exibido quando o campo não tiver a quantidade mínima de caracteres.
 * errorMax: Erro a ser exibido quando o campo não tiver a quantidade máxima de caracteres.
 * errorMinMax: Erro a ser exibido quando o campo não tiver uma quantidade adequada de caracteres (mínimo ou máximo).
 * errorFormat: Erro a ser exibido quando o formato dos dados estiver inválido (não bater com a expressão regular).
 * transform: Função a ser usada para transformar os dados antes do envio (remover espaços, criar hash, etc).
 *
 * @author Dimas Melo Filho
 * @param  nome Nome do cadastro.
 * @param  table arquivo ajax a ser utilizado ou nome da tabela a ser gravada pelo cadastro universal.
 * @param  fields lista de campos, formatada conforme descrito acima.
 */
function fmCadastro(nome, table, fields) {
	this.cadastro = nome;
	this.table = table;
	this.fields = fields;
	this.preprocessFields();
}

/**
 * Função usada para preprocessar os campos fornecidos pelo usuário, validando e preenchendo propriedades vazias.
 *
 * @author Dimas Melo Filho
 */
fmCadastro.prototype.preprocessFields = function() {
	for (var f = 0; f < this.fields.length; f++) {
		if (this.fields[f].id == null) throw "fmCadastro inicializado com campo sem ID.";
		var obj = document.getElementById(this.fields[f].id);
		if (obj == null) {
			alert("fmCadastro: Não foi possível encontrar o elemento com id '" + this.fields[f].id + "'.");
			return;
		}
		this.fields[f].obj = obj;
		if (this.fields[f].name == null) 
			this.fields[f].name = obj.getAttribute('name');
		if (this.fields[f].nameEx == null) 
			this.fields[f].nameEx = this.fields[f].name;
		if (this.fields[f].bd == null) 
			this.fields[f].bd = this.fields[f].name;
		if (this.fields[f].minChars == null) 
			this.fields[f].minChars = 0;
		if (this.fields[f].maxChars == null) 
			this.fields[f].maxChars = 0;
		if (this.fields[f].errorMin == null && this.fields[f].minChars > 0) 
			this.fields[f].errorMin = "Erro no campo \"%nameEx%\". O campo deve conter no mínimo %minChars% caracteres.";
		if (this.fields[f].errorMax == null && this.fields[f].maxChars > 0)
			this.fields[f].errorMax = "Erro no campo \"%nameEx%\". O campo deve conter no máximo %maxChars% caracteres.";
		if (this.fields[f].errorFormat == null && this.fields[f].regex != null)
			this.fields[f].errorFormat = "Os dados de \"%nameEx%\" informados são inválidos. Verifique o campo e tente novamente.";
	}
}

/**
 * Função que efetiva um cadastro. O nome está em português porque não encontrei nenhuma palavra semelhante em inglês.
 * A função executa validação, exibe erros e envia os dados para o ajax, chama alguma função personalizada quando o ajax for concluído,
 * 
 * @author Dimas Melo Filho
 */
fmCadastro.prototype.cadastrar = function() {
	var postData = this.getPostData();
	var thisCad = this;
	if (postData != null) {
		postData['_op'] = 'cadastrar'; // Operação
		$.ajax(this.table,
			{
				error: this.ajaxError,
				success: function (data) { thisCad.ajaxSuccess(data); }, // Funcao anonima para passar o THIS correto.
				data: postData,
				method: 'POST'
			}
		);
	}
}

/**
 * Função que armazena os dados no banco em um novo registro.
 *
 * @author Dimas Melo Filho
 * @param avisarOk indica se o sistema deve avisar quando a inserção for bem sucedida.
 */
fmCadastro.prototype.insert = function(ignorar, fnFim) {
	var postData = this.getPostData();
	var thisCad = this;
	if (ignorar == null) ignorar = 0;
	if (postData != null) {
		postData['___t'] = this.table;		// Tabela
		postData['___o'] = 'insert';		// Operação
		var query = "";
		for (k in postData) query += k + '=' + postData[k] + '&'; 
		alert(query);
		$.ajax('Cadastro.php',
			{
				error: this.ajaxError,
				success: function (data) { console.debug(data); thisCad.ajaxSuccess(data, ignorar, fnFim); },
				data: postData,
				method: 'POST'
			}
		);
	}
}

/**
 * Função que efetiva uma atualização.
 * A função executa validação, exibe erros e envia os dados para o ajax, chama alguma função personalizada quando o ajax for concluído.
 *
 * @author Dimas Melo Filho
 */
fmCadastro.prototype.update = function(ignorar, fnFim) {
	var postData = this.getPostData();
	var thisCad = this;
	if (ignorar == null) ignorar = 0;
	if (postData != null) {
		postData['___t'] = this.table;		// Tabela
		postData['___o'] = 'update'; 		// Operação
		$.ajax('Cadastro.php',
			{
				error: this.ajaxError,
				success: function (data) { thisCad.ajaxSuccess(data, ignorar, fnFim); }, // Funcao anonima para pasar o THIS correto.
				data: postData,
				method: 'POST'
			}
		);
	}
}

/**
 * Função chamada quando o ajax retorna um sucesso.
 *
 * @author Dimas Melo Filho
 * @param data dados de retorno da comunicação.
 * @param avisarOk true se for para exibir caixa de mensagens quando o resultado for ok.
 * @param fnFim função para ser chamada quando a conexão for finalizada
 */
fmCadastro.prototype.ajaxSuccess = function(data, ignorar, fnFim) {
	var thisCad = this;
	if (ignorar == null) ignorar = 0;
	if (data.charAt(0) != "#") {
		if ((ignorar & 1) == 0)
			def.modalBox(['OK'],"Sucesso","O cadastro foi realizado com sucesso.", function() { thisCad.clearFields(); });
		if (typeof(fnFim) == "function") fnFim();
	} else {
		var erro = data.substr(1).split(':>>>',2);
		if ((ignorar & 2) == 0) {
			if (erro.length >= 2 && erro[0].length > 0) {
				def.modalBox(
					['OK','Ajuda'],
					"Erro", 
					erro[1], 
					function(b) { 
						if (b == 'Ajuda') fmTabNew('manual',erro[0],"Ajuda do Gehos");
					}
				);
			} else {
				def.modalBox(['OK'],"Erro",data,null);
			}
		}
		if (typeof(fnFim) == "function") fnFim(erro[0], erro[1]);
	}
}

/**
 * Função que limpa os campos do cadastro.
 */
fmCadastro.prototype.clearFields = function() {
	for (var f = 0; f < this.fields.length; f++) {
		switch (this.fields[f].obj.tagName.toLowerCase()) {
			case 'select':
				this.fields[f].obj.selectedIndex = -1;
				break;
			case 'textarea':
				this.fields[f].obj.value = "";
				break;
			case 'input':
				switch (this.fields[f].obj.getAttribute('type')) {
					case 'radio':
					case 'checkbox':
						this.fields[f].obj.checked = false;
						break;
					default:
						this.fields[f].obj.value = "";
						break;
				}
				break;				
			default:
				this.fields[f].obj.innerHTML == "";
				break;
		}
	}
}

/**
 * Função chamada quando o ajax retorna um erro.
 *
 * @author Dimas Melo Filho
 */
fmCadastro.prototype.ajaxError = function(xhr, errType, errText) {
	if (errType == "timeout") {
		def.modalBox(['OK','Ajuda'],"Erro de Conexão","Ocorreu um erro com a conexão do servidor. O tempo de conexão expirou e o servidor não respondeu.", this.clickErrorTimeout);
	}
	else if (errType == "parsererror") {
		def.modalBox(['OK','Ajuda'],"Erro de Conexão","Ocorreu um erro com a conexão do servidor. Os dados retornados são inválidos.", this.clickErrorParserError);
	}
	else if (errType == "abort") {
		def.modalBox(['OK','Ajuda'],"Erro de Conexão","Ocorreu um erro com a conexão do servidor. A conexão foi abortada.", this.clickErrorAbort);
	}
	else {
		if (errText != null && errText.length > 0) {
			if (errText == 'Not Found') {
				def.modalBox(['OK','Ajuda'],"Erro de Conexão","Ocorreu um erro com a conexão do servidor. O arquivo do módulo não foi encontrado.", this.clickErrorNotFound);
			} else {
				def.modalBox(['OK','Ajuda'],"Erro de Conexão","Ocorreu um erro desconhecido ao tentar a conexão com o servidor. O texto retornado pelo servidor foi: "+ errText, this.clickErrorUnknownMessage);
			}
		}
		else {
			def.modalBox(['OK','Ajuda'],"Erro de Conexão","Ocorreu um erro desconhecido ao tentar a conexão com o servidor. O servidor não informou nenhum código de erro.", this.clickErrorNotUnknown);
		}
	}
}

/**
 * Função que obtém os valores de cada campo.
 *
 */
fmCadastro.prototype.getPostData = function() {
	var postData = {};
	for (var f = 0; f < this.fields.length; f++) {
		var fieldName = this.fields[f].name;
        switch (this.fields[f].obj.tagName.toLowerCase()) {
            case 'select':
                postData[fieldName] = this.fields[f].obj.options[this.fields[f].obj.selectedIndex].value;
                break;
            case 'textarea':
                postData[fieldName] = this.fields[f].obj.value;
                break;
            case 'input':
				if (this.fields[f].obj.getAttribute('type') == null) alert("ERRO: Campo sem type: " + this.fields[f].obj.name);
                switch (this.fields[f].obj.getAttribute('type').toLowerCase()) {
                    case 'radio':
                        if (postData[fieldName] == null)
                            this.fields[f].value = this.getRadioValue(this.fields[f].name);
                        break;
                    case 'checkbox':
                        postData[fieldName] = this.fields[f].obj.checked;
                        break;
                    default:
                        postData[fieldName] = (this.fields[f].obj.realValue != null ? this.fields[f].obj.realValue : this.fields[f].obj.value);
                        break;
                }
                break;
            default:
                postData[fieldName] = this.fields[f].obj.innerHTML;
                break;
        }
		if (!this.validate(f, postData[fieldName])) return null;
		if (this.fields[f].transform != null) postData[fieldName] = this.fields[f].transform(postData[fieldName]);
	}
	return postData;
}

fmCadastro.prototype.getFieldValueByName = function(fieldName) {
    for (var f = 0; f < this.fields.length; f++)
        if (this.fields[f].name == fieldName)
            switch (this.fields[f].obj.tagName.toLowerCase()) {
                case 'select': return this.fields[f].obj.options[this.fields[f].obj.selectedIndex].value;
                case 'textarea': return this.fields[f].obj.value;
                case 'input':
                    switch (this.fields[f].obj.getAttribute('type').toLowerCase()) {
                        case 'radio': return this.getRadioValue(this.fields[f].name);
                        case 'checkbox': return this.fields[f].obj.checked;
                        default: return this.fields[f].obj.value;
                    }
                default: return this.fields[f].obj.innerHTML;
            }
}

fmCadastro.prototype.getFieldValueByIndex = function(f) {
    switch (this.fields[f].obj.tagName.toLowerCase()) {
        case 'select': return this.fields[f].obj.options[this.fields[f].obj.selectedIndex].value;
        case 'textarea': return this.fields[f].obj.value;
        case 'input':
            switch (this.fields[f].obj.getAttribute('type').toLowerCase()) {
                case 'radio': return this.getRadioValue(this.fields[f].name);
                case 'checkbox': return this.fields[f].obj.checked;
                default: return this.fields[f].obj.value;
            }
        default: return this.fields[f].obj.innerHTML;
    }
}

/**
 * Função chamada para validar um campo específico antes de realizar o cadastro.
 *
 * @author Dimas Melo Filho.
 * @param  fieldIndex número índice do campo na lista de campos.
 * @param  value valor atual do campo.
 */
fmCadastro.prototype.validate = function(fieldIndex, value) {
	if (value.length < this.fields[fieldIndex].minChars) {
		var msg = this.fields[fieldIndex].errorMinMax != null ? this.fields[fieldIndex].errorMinMax : this.fields[fieldIndex].errorMin;
		this.showError(fieldIndex, msg);
		return false;
	}
	if (this.fields[fieldIndex].maxChars > 0 && value.length > this.fields[fieldIndex].maxChars) {
		msg = this.fields[fieldIndex].errorMinMax != null ? this.fields[fieldIndex].errorMinMax : this.fields[fieldIndex].errorMax;
		this.showError(fieldIndex, msg);
		return false;
	}
	if (this.fields[fieldIndex].regex != null && value.match(this.fields[fieldIndex].regex) == null) {
		this.showError(fieldIndex, this.fields[fieldIndex].errorFormat);
		return false;
	}
	if (this.fields[fieldIndex].validation != null) {
		for (var v = 0; v < this.fields[fieldIndex].validation.length; v++) {
			var res = this.fields[fieldIndex].validation[v](value);
			if (res != true) {
				this.showError(fieldIndex, res);
				return false;
			}
		}
	}
	return true;
}

/**
 * Função que obtém o valor de um radio.
 *
 * @author Dimas Melo Filho
 * @param  radioName Nome do grupo de botões de radio.
 * @return valor do botão selecionado ou null se não houver nenhum botão selecionado
 */
fmCadastro.prototype.getRadioValue = function(radioName) {
	for (var f = 0; f < this.fields.length; f++)
		if (this.fields[f].name == radioName && this.fields[f].obj.checked)
			return this.fields[f].obj.value;
	return null;
}

/**
 * Função chamada quando o usuário clica em algum botão da caixa modal de erro de conexão por timeout.
 *
 * @author Dimas Melo Filho
 * @param  button Texto do botão clicado.
 */
fmCadastro.prototype.clickErrorTimeout = function(button) {
	if (button == 'Ajuda') fmTabNew('manual','ajudaCadastroErroConexaoTimeout.php?cadastro=' + encode(this.cadastro),'Ajuda de Erro');			
}

/**
 * Função chamada quando o usuário clica em algum botão da caixa modal de erro de conexão por timeout.
 *
 * @author Dimas Melo Filho
 * @param  button Texto do botão clicado.
 */
fmCadastro.prototype.clickErrorParserError = function(button) {
	if (button == 'Ajuda') fmTabNew('manual','ajudaCadastroErroConexaoParserError.php?cadastro=' + encode(this.cadastro),'Ajuda de Erro');			
}

/**
 * Função chamada quando o usuário clica em algum botão da caixa modal de erro de conexão por timeout.
 *
 * @author Dimas Melo Filho
 * @param  button Texto do botão clicado.
 */
fmCadastro.prototype.clickErrorAbort = function(button) {
	if (button == 'Ajuda') fmTabNew('manual','ajudaCadastroErroConexaoAbort.php?cadastro=' + encode(this.cadastro),'Ajuda de Erro');			
}

/**
 * Função chamada quando o usuário clica em algum botão da caixa modal de erro de conexão por timeout.
 *
 * @author Dimas Melo Filho
 * @param  button Texto do botão clicado.
 */
fmCadastro.prototype.clickErrorNotFound = function(button) {
	if (button == 'Ajuda') fmTabNew('manual','ajudaCadastroErroConexaoNotFound.php?cadastro=' + encode(this.cadastro),'Ajuda de Erro');			
}

/**
 * Função chamada quando o usuário clica em algum botão da caixa modal de erro de conexão por timeout.
 *
 * @author Dimas Melo Filho
 * @param  button Texto do botão clicado.
 */
fmCadastro.prototype.clickErrorUnknownMessage = function(button) {
	if (button == 'Ajuda') fmTabNew('manual','ajudaCadastroErroConexaoUnknownMessage.php?cadastro=' + encode(this.cadastro),'Ajuda de Erro');			
}

/**
 * Função chamada quando o usuário clica em algum botão da caixa modal de erro de conexão por timeout.
 *
 * @author Dimas Melo Filho
 * @param  button Texto do botão clicado.
 */
fmCadastro.prototype.clickErrorUnknown = function(button) {
	if (button == 'Ajuda') fmTabNew('manual','ajudaCadastroErroConexaoUnknown.php?cadastro=' + encode(this.cadastro),'Ajuda de Erro');			
}

/**
 * Função chamada para exibir um erro.
 *
 * @author Dimas Melo Filho
 * @param  fieldIndex índice do campo na lista.
 * @param  msg Mensagem a ser exibida.
 */
fmCadastro.prototype.showError = function(fieldIndex, msg) {
	msg = msg.replace(/%nameEx%/gi, this.fields[fieldIndex].nameEx).
		replace(/%minChars%/gi,this.fields[fieldIndex].minChars).
		replace(/%maxChars%/gi,this.fields[fieldIndex].maxChars);
	var fields = this.fields;
	def.modalBox(['OK'],'Erro de Cadastro',msg,function(button) {
		dBlink($(fields[fieldIndex].obj));
		fields[fieldIndex].obj.focus();
	});
}

/**
 * Função chamada para remover um registro da lista.
 *
 * @author Dimas Melo Filho
 * @param  cod Código do registro no banco de dados.
 * @param  rowNum número da linha
 */
fmCadastro.prototype.remove = function(cod, rowNum) {
	var postData = {_op: 'remover', "codigo": cod, "idLinha": rowNum};
	var f = this;
	$.ajax(this.table,
		{
			error: this.ajaxError,
			success: function(data) { f.ajaxSuccessRemove(data, rowNum) },
			data: postData,
			method: 'POST'
		}
	);
}

/**
 * Função chamada quando o ajax retorna um sucesso.
 */
fmCadastro.prototype.ajaxSuccessRemove = function(data, rowNum) {
	if (data.charAt(0) != "#") {
	} else {
		var erro = data.substr(1).split(':>>>',2);
		if (erro.length >= 2 && erro[0].length > 0) {
			def.modalBox(
				['OK','Ajuda'],
				"Erro", 
				erro[1], 
				function(b) { 
					if (b == 'Ajuda') fmTabNew('manual',erro[0],"Ajuda do Gehos");
				}
			);
		} else {
			def.modalBox(['OK'],"Erro",data,null);
		}
	}	
}




