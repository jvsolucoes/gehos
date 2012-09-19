/**
 * Framework para gerenciamento de Cadeias de Eventos.
 * por Dimas Melo Filho
 */
 
 
/**
 * fun��o indexOf para Arrays para compatibilidade com Internet Explorer <= 7
 *
 * @author Dimas Melo Filho
 * @param  o Objeto a ser procurado.
 */
if (!Array.indexOf) {
	Array.prototype.indexOf = function(o){
		for(var i=0; i<this.length; i++) if(this[i]==o) return i;
		return -1;
	}
}

/**
 * Objeto def. Cont�m todas as fun��es do framework.
 *
 * @author Dimas Melo Filho
 */
var def = {

	/*******************************************************************
	 * Fun��es relacionadas ao gerenciamento de eventos.
	 *
	 */
	 
	 /**
	  * Adiciona um listener de evento.
	  *
	  * @author Dimas Melo Filho
	  * @param  element Elemento para o qual ser� criado o listener.
	  * @param  eventName nome do evento. P.ex. 'onkeydown'.
	  * @param  listener A fun��o listener do usu�rio.
	  * @param  chainType Tipo de cadeia, pode ser 'ontrue', 'onfalse', 'nochain' ou 'onany'. Esse par�metro determina a propaga��o do evento.
	  */
	addEventListener: function(element, eventName, listener, chainType) {
		eventName = eventName.toLowerCase();
		if (element == null) throw "Elemento Inválido para o fieldNav.";
		if (typeof(element[eventName]) != 'function') {
			if (typeof(chainType) != 'string') chainType = 'ontrue';
			this.chainType = chainType.toLowerCase();
			switch (chainType) {
				case 'ontrue': element[eventName] = this.eventListenerOnTrue; break;
				case 'onfalse': element[eventName] = this.eventListenerOnFalse; break;
				case 'nochain': element[eventName] = this.eventListenerNoChain; break;
				default: element[eventName] = this.eventListenerOnAny; break;
			}
		}
		var listenerList = eventName + 'defl';
		if (typeof(element[listenerList]) == 'undefined') element[listenerList] = [];
		element[listenerList].push(listener);
	},
	
	/**
	 * Remove um listener de evento.
	 *
	 * @author Dimas Melo Filho
	 * @param  element Elemento que possui o listener a ser removido.
	 * @param  eventName Nome do evento que ter� o listener removido.
	 * @param  listener fun��o que ser� removida.
	 */
	removeEventListener: function(element, eventName, listener) {
		var listenerList = eventName + 'defl';
		var listenerIdx = element[listenerList].indexOf(listener);
		if (listenerIdx >= 0) element[listenerList].splice(listenerIdx,1);
	},
	
	/*******************************************************************
	 * Gerenciadores de eventos para v�rios tipos de propaga��o.
	 * 
	 */
	eventListenerOnTrue: function(event) {
		event = (event) ? event : window.event;
		var listenerList = 'on' + event.type + 'defl';
		for (var i = 0; i < this[listenerList].length; i++) {
			if (this[listenerList][i](event, this) == false) return false;
		}
		return true;
	},
	eventListenerOnFalse: function(event) {
		event = (event) ? event : window.event;
		var listenerList = 'on' + event.type + 'defl';
		for (var i = 0; i < this[listenerList].length; i++) {
			if (this[listenerList][i](event, this) == true) return true;
		}
		return false;
	},
	eventListenerNoChain: function(event) {
		event = (event) ? event : window.event;
		var listenerList = 'on' + event.type + 'defl';
		if (this[listenerList].length > 0) return this[listenerList][i](event, this);
	},
	eventListenerOnAny: function(event) {
		event = (event) ? event : window.event;
		var listenerList = 'on' + event.type + 'defl';
		var ret = true;
		for (var i = 0; i < this[listenerList].length; i++) {
			if (this[listenerList][i](event, this) == false) ret = false;
		}
		return ret;
	},
	
	/*******************************************************************
	 * Fun��es para navega��o entre campos com a tecla enter e as setas do teclado.
	 *
	 */
	fieldNavList: [],
	
	/**
	 * Inicializa o script de navega��o entre campos nos campos designados na lista.
	 *
	 * @author Dimas Melo Filho
	 * @param  tabId id da aba que cont�m os objetos.
	 * @param  objList lista ordenda de objetos e fun��es de navega��o.
	 * A lista deve conter os ids dos objetos na ordem de navega��o.
	 * A lista tamb�m pode conter fun��es para serem executadas durante uma transi��o de navega��o espec�fica entre dois campos.
	 * Para colocar uma fun��o na lista, basta come�ar o elemento que conter� a fun��o com o caractere '@' ou '%'.
	 * A diferen�a � que os iniciados com '@' s� s�o executados quando a navega��o ocorre com a tecla enter.
	 * J� os iniciados com '%' s�o executados independente da tecla de navega��o utilizada.
	 */
	fieldNav: function(tabId, objList) {
		/* Remover navega��o anterior da aba */
		for (var j = 0; j < this.fieldNavList.length; j++) 
			if (this.fieldNavList[j].tabId == tabId) {
				for (var k = 0; k < this.fieldNavList[j].fields; k++)
					this.removeEventListener(document.getElementById(this.fieldNavList[j].fields[k]), 'onkeydown', this.fieldNavKeyDown);
				this.fieldNavList.splice(j,1); 
				break; 
			}
		/* Adicionar nova navega��o */
		var i = this.fieldNavList.push({"tabId": tabId, fields: objList, lastSelected: null}) - 1;
		for (var j = 0; j < this.fieldNavList[i].fields.length; j++) {
			if ("%@".indexOf(this.fieldNavList[i].fields[j].charAt(0)) == -1) {
				var obj = document.getElementById(this.fieldNavList[i].fields[j]);
				this.addEventListener(obj, 'onkeydown', this.fieldNavKeyDown, 'ontrue');
			}
		}
		if (this.fieldNavList != null && this.fieldNavList[i].fields[0] != null) {
			this.fieldNavList[i].lastSelected = 0;
			document.getElementById(this.fieldNavList[i].fields[0]).focus();
		}
	},
	
	/**
	 * Encontra a refer�ncia para o campo na lista de refer�ncias de navega��o de campos. A busca � feita por ID do campo.
	 *
	 * @author Dimas Melo Filho
	 * @param  fieldId id do campo.
	 */
	fieldNavFindRefById: function(fieldId) {
		for (var i = 0; i < this.fieldNavList.length; i++) {
			for (var j = 0; j < this.fieldNavList[i].fields.length; j++) {
				if (this.fieldNavList[i].fields[j] == fieldId) return [i, j, this.fieldNavList[i].tabId];
			}
		}
		return null;
	},
	
	/**
	 * Encontra a refer�ncia para o campo na lista de refer�ncias de navega��o de campos. A busca � feita por ID da aba.
	 *
	 * @author Dimas Melo Filho
	 * @param  tabId id da aba.
	 */
	fieldNavFindRefByTab: function(tabId) {
		for (var i = 0; i < this.fieldNavList.length; i++) {
			if (this.fieldNavList[i].tabId == tabId) return [i, this.fieldNavList[i].tabId];
		}
		return null;
	},

	/**
	 * Navega para o pr�ximo campo
	 *
	 * @author Dimas Melo Filho
	 * @param  objRef Array com a refer�ncia do objeto atual
	 * @param  ret foi um enter?
	 */
	fieldNavNext: function(objRef, ret) {
		/* Calcular o pr�ximo item a ser selecionado */
		var next = objRef[1] + 1;
		if (next >= this.fieldNavList[objRef[0]].fields.length) next = 0;
		var nextId = this.fieldNavList[objRef[0]].fields[next];
		/* Salvar o ID do pr�ximo a ser selecionado como �ltimo que foi selecionado */
		this.fieldNavList[objRef[0]].lastSelected = next;
		/* Procedimento para sele��o do pr�ximo campo */
		objRef[1] = next;
		if (nextId.charAt(0) == '%') { //Func JS
			if (eval(nextId.substring(1))) this.fieldNavNext(objRef, ret);
		} else if (nextId.charAt(0) == '@') { //Func JS s� para a tecla enter
			if (ret) {
				if (eval(nextId.substring(1))) this.fieldNavNext(objRef, ret);
			} else {
				this.fieldNavNext(objRef, ret);
			}
		} else { // Campo para selecionar
			document.getElementById(nextId).focus();
		}
	},

	/**
	 * Navega para o campo anterior
	 *
	 * @author Dimas Melo Filho
	 * @param  objRef Array com a refer�ncia do objeto atual
	 * @param  ret foi um enter?
	 */
	fieldNavPrev: function(objRef, ret) {
		/* Calcular o item anterior a ser selecionado */
		var prev = objRef[1] - 1;
		if (prev < 0) prev = this.fieldNavList[objRef[0]].fields.length - 1;
		var prevId = this.fieldNavList[objRef[0]].fields[prev];
		/* Salvar o ID do anterior a ser selecionado como �ltimo que foi selecionado */
		this.fieldNavList[objRef[0]].lastSelected = prev;
		/* Procedimento para sele��o do campo anterior */
		objRef[1] = prev;
		if (prevId.charAt(0) == '%') { //Func JS
			if (eval(prevId.substring(1))) this.fieldNavPrev(objRef, ret);
		} else if (prevId.charAt(0) == '@') { //Func JS s� para a tecla enter
			if (ret) {
				if (eval(prevId.substring(1))) this.fieldNavPrev(objRef, ret);
			} else {
				this.fieldNavPrev(objRef, ret);
			}
		} else { // Campo para selecionar
			document.getElementById(prevId).focus();
		}
	},

	/**
	 * Handler de evento Onkeydown que invoca fun��o de navega��o entre campos com o teclado.
	 *
	 * @author Dimas Melo Filho
	 * @param  event dados do evento.
	 * @param  obj objeto onde ocorreu o evento.
	 */
	fieldNavKeyDown: function(event, obj) {
		var k = (window.event ? window.event.keyCode : event.which);
		if (event.altKey || event.shiftKey || event.ctrlKey) return true;
		var objRef = def.fieldNavFindRefById(obj.id);
		if (objRef != null) {
			switch (k) {
				case 13: //Enter
					def.fieldNavNext(objRef, true);
					return false;
				case 38: //Seta para Cima
					def.fieldNavPrev(objRef, false);
					return false;
				case 40: //Seta para Baixo
					def.fieldNavNext(objRef, false);
					return false;
				default:
					return true;
			}
		}
		return true;
	},
	
	/**
	 * Seleciona o �ltimo campo que foi selecionado na aba. Atualmente a fun��o � usada no arquivo tabs.js
	 *
	 * @author Dimas Melo Filho
	 * @param  tabId id da aba que se deseja selecionar o �ltimo campo selecionado.
	 */
	fieldNavSelectLast: function(tabId) {
		var objRef = this.fieldNavFindRefByTab(tabId);
		if (objRef != null) {
			var last = this.fieldNavList[objRef[0]].lastSelected;
			var lastId = this.fieldNavList[objRef[0]].fields[last];
			if (lastId != null && "%@".indexOf(lastId.charAt(0)) == -1) { 
				var obj = document.getElementById(lastId);
				if (obj != null) obj.focus();
			}
		}
	},
	
	/**
	 * Remove os elementos de navega��o de uma aba.
	 */
	fieldNavRemove: function(tabId) {
		var objRef = this.fieldNavFindRefByTab(tabId);
		this.fieldNavList.splice(objRef[0],1);
	},
	
	/*******************************************************************
	 * Fun��es que gerenciam os atalhos de teclado.
	 *
	 */
	 
	/**
	 * Indica se � o primeiro atalho criado no documento.
	 */
	firstShortcut: true,
	/**
	 * Cont�m a lista de lista de atalhos. Formato: shortcutList[tecla][0..n] lista os atalhos de 0..n da tecla.
	 */
	shortcutList: [],
	/**
	 * Teclas de atalho usadas para decodifica��o.
	 */
	keyCodes: {
		backspace:8, tab:9, enter:13, pause:19, caps:20, esc:27, 
		pageup:33, pagedown:34, end:35, home:36, left:37, up:38, right:39, down:40, insert:45, "delete":46, select:93,
		numpad0:96, numpad1:97, numpad2:98, numpad3:99, numpad4:100, numpad5:101, numpad6:102, numpad7:103, numpad8:104, numpad9:105,
		multiply:106, add:107, subtract:109, decimal:110, divide:111,
		f1:112, f2:113, f3:114, f4:115, f5:116, f6:117, f7:118, f8:119, f9:120, f10:121, f11:122, f12:123
	},

	
	/**
	 * Adiciona um atalho.
	 *
	 * @author Dimas Melo Filho
	 * @param  shortcut Teclas de atalho.
	 * @param  listener Fun��o(event,obj) que ser� chamada quando o atalho for utilizado.
	 */
	addShortcut: function(shortcut, listener) {
		if (this.firstShortcut) {
			this.addEventListener(document, 'onkeydown', this.shortcutKeyDown, 'ontrue');
			this.firstShortcut = false;
		}
		var sc = this.decodeShortcut(shortcut);
			if (sc.keyCode != null) {
			var keyCode = sc.keyCode;
			delete sc.keyCode;
			sc.listener = listener;
			if (typeof(this.shortcutList[keyCode]) == 'undefined') this.shortcutList[keyCode] = [];
			this.shortcutList[keyCode].push(sc);
		}
	},
	
	/**
	 * Remove um atalho.
	 *
	 * @author Dimas Melo Filho
	 * @param  shortcut Teclas de atalho.
	 */
	removeShortcut: function(shortcut) {
		var sc = this.decodeShortcut(shortcut);
		if (sc.keyCode != null && this.shortcutList[sc.keyCode] != null) {
			for (var i = 0; i < this.shortcutList[sc.keyCode]; i++) {
				if (this.shortcutList[sc.keyCode][i].listener == listener) this.shortcutList[sc.keyCode].splice(i,1);
			}
		}				
	},
	
	/**
	 * Decodifica um atalho e retorna uma estrutura de atalho (sc).
	 *
	 * @author Dimas Melo Filho
	 * @param  shortcut Teclas de atalho.
	 */
	decodeShortcut: function(shortcut) {
		var keys = shortcut.toLowerCase().split('+');
		var sc = {
			ctrl: false,
			shift: false,
			alt: false,
			keyCode: null
		};
		for (var k = 0; k < keys.length; k++) {
			switch (keys[k]) {
				case 'ctrl': sc.ctrl = true; break;
				case 'alt': sc.alt = true; break;
				case 'shift': sc.shift = true; break;
				default: sc.keyCode = this.translateKeyCode(keys[k]);
			}
		}
		return sc;
	},
	
	/**
	 * Retorna o c�digo de uma tecla n�o-especial (sem ser ctrl, alt ou shift).
	 *
	 * @author Dimas Melo Filho
	 * @param  keyName Nome da tecla.
	 */
	translateKeyCode: function(keyName) {
		return keyName.length == 1 ? keyName.toUpperCase().charCodeAt(0) : this.keyCodes[keyName];
	},
	
	/**
	 * Handler do evento keydown que ativa os atalhos.
	 *
	 * @author Dimas Melo Filho
	 * @param  event evento onkeydown
	 * @param  obj objeto que causou o evento
	 */
	shortcutKeyDown: function(event, obj) {
		var k = ('which' in event) ? event.which : event.keyCode;
		if (def.shortcutList[k] != null)
		for (var i = 0; i < def.shortcutList[k].length; i++) {
			var sc = def.shortcutList[k][i];
			if (sc != null) {
				if (sc.ctrl != event.ctrlKey) continue;
				if (sc.alt != event.altKey) continue;
				if (sc.shift != event.shiftKey) continue;
				return sc.listener(event, obj);
			}
		}
		return true;
	},
	
	/*******************************************************************
	 * M�todos para inclus�o din�mica de arquivos Javascript
	 * Os arquivos s�o incluidos sem repeti��o. O m�dulo verifica se h� repeti��o antes de incluir.
	 * N�o requer nenhum script adicional.
	 */
 
	/**
	 * Inclui um script � p�gina atual.
	 *
	 * @author Dimas Melo Filho
	 * @param  file Arquivo a ser inclu�do.
	 * @return bool True se adicionar o script, false caso contr�rio.
	 */
	include: function(file) {
		var head = document.getElementsByTagName('head')[0];
		if (head == null) throw "O documento não possui uma tag head";
		if (!this.included(file, head)) {
			var el = document.createElement('script');
			el.setAttribute("type",'text/javascript');
			el.setAttribute("src",file);
			head.appendChild(el);
			return true;
		}
		return false;
	},

	/**
	 * Verifica se um script j� foi inclu�do.
	 *
	 * @author Dimas Melo Filho
	 * @param  file Script a ser verificado.
	 * @param  head Elemento head.
	 * @return bool True se j� existir um script com o mesmo nome, false caso contr�rio.
	 */
	included: function(file, head) {
		for (var i = 0; i < head.childNodes.length; i++) {
			var tag = head.childNodes[i].tagName;
			if (tag != null && tag.toLowerCase() == 'script' && head.childNodes[i].src == file) return true;
		}
		return false;
	},
	
	/*******************************************************************
	 * Fun��es para cria��o de Di�logo Modal.
	 */
	
	/**
	 * Vari�vel que cont�m o div externo que escurece a tela.
	 */
	modalDivE: null,
	/**
	 * Vari�vel que cont�m o div interno que cont�m o conte�do.
	 */
	modalDivI: null,
	/**
	 * Vari�vel que cont�m o div com o t�tulo da janela modal.
	 */
	modalDivT: null,
	/**
	 * Vari�vel que cont�m o div com o conte�do da janela modal.
	 */
	modalDivC: null,
	/**
	 * Vari�vel que cont�m o div com os bot�es da janela modal.
	 */
	modalDivB: null,
	/**
	 * Vari�vel que cont�m a fun��o que deve ser chamada no resultado da tela modal.
	 */
	modalResultFunction: null,
	
	/**
	 * Inicializa os elementos necess�rios para o di�logo modal na p�gina.
	 *
	 * @author Dimas Melo Filho
	 */
	init_modal: function() {
		/* Cria e configura a div externa */
		this.modalDivE = document.createElement('div');
		this.css(
			this.modalDivE, 
			{
				backgroundColor:'#001', 
				position: 'absolute', 
				display: 'none', 
				top: '0', 
				left: '0', 
				opacity: '0.6', 
				zIndex: '1000', 
				visibility: 'hidden'
			}
		);
		this.addEventListener(this.modalDivE, 'onmousedown', this.modalBlockMouse, 'ontrue');
		/* Cria e configura a div interna */
		this.modalDivI = document.createElement('div');	
		this.modalDivI.className = "defModalWindow";
		this.css(
			this.modalDivI, 
			{
				position: 'absolute', 
				display: 'none', 
				opacity: '1', 
				zIndex: '1001', 
				visibility: 'hidden'
			}
		);
		/* Cria e configura a div de t�tulo da janela modal */
		this.modalDivT = document.createElement('div');
		this.modalDivT.className = "defModalTitle";
		this.modalDivI.appendChild(this.modalDivT);
		/* Cria e configura o div de conte�do da janela modal */
		this.modalDivC = document.createElement('div');
		this.modalDivC.className = "defModalContent";
		this.modalDivI.appendChild(this.modalDivC);
		/* Cria e configura o div de bot�es da janela modal */
		this.modalDivB = document.createElement('div');
		this.modalDivB.className = "defModalButtons";
		this.modalDivI.appendChild(this.modalDivB);
		/* Adiciona os divs ao body */
		document.body.appendChild(this.modalDivI);
		document.body.appendChild(this.modalDivE);
		/* Remove a fun��o de inicializa��o */
		delete this.init_modal;
	},
	
	/**
	 * Mostra a janela modal. OBS: os bot�es utilizam a estiliza��o das classes btn_c, btn_e, btn_d para normal e btn_cs, btn_es, btn_ds para highlight.
	 *
	 * @author Dimas Melo Filho
	 * @param  btn Bot�es da janela.
	 * @param  title T�tulo da janela.
	 * @param  msg Mensagem da janela (html, pode conter qualquer tag HTML).
	 * @param  resFunc fun��o que ser� chamada quando o usu�rio clicar em um bot�o. A fun��o passa como parametro �nico o texto do bot�o que foi clicado.
	 */
	modalBox: function(btn, title, msg, resFunc) {
		/* Inicializa os elementos de janela modal se for a primeira vez */
		if (this.init_modal != null) this.init_modal();
		/* Define o t�tulo e a mensagem */
		this.modalDivT.innerHTML = title;
		this.modalDivC.innerHTML = msg;
		/* Define os bot�es */
		var buttons = '';
		for (var b = 0; b < btn.length; b++) {
			buttons += '<div class="btn_c"><div class="btn_e"><div class="btn_d" onmouseover="def.buttonOver(this)" onmouseout="def.buttonOut(this)" onclick="def.modalButtonClick(this)">' + btn[b] + '</div></div></div>';
		}
		this.modalDivB.innerHTML = buttons;
		/* Configura o listener de evento para bloquear o mouse e a fun��o de resultado da janela modal */
		this.addEventListener(window, 'onresize', def.modalResize, 'ontrue');
		this.modalResultFunction = resFunc;
		/* Mostra a tela e redimensiona os componentes de acordo com o tamanho da janela. */
		this.modalDivE.style.display = "block";
		this.modalDivI.style.display = "block";		
		this.modalResize();
		this.modalDivE.style.visibility = '';
		this.modalDivI.style.visibility = '';
	},
	
	/**
	 * Fun��o chamada quando o usu�rio clica em um bot�o da janela modal.
	 *
	 * @author Dimas Melo Filho
	 * @param  btnObj Elemento bot�o que foi clicado.
	 */
	modalButtonClick: function(btnObj) {
		if (def.modalResultFunction != null) def.modalResultFunction(btnObj.innerHTML);
		def.hideModalBox();
	},
	
	/**
	 * Oculta a janela modal.
	 *
	 * @author Dimas Melo Filho
	 */
	hideModalBox: function() {
		def.modalResultFunction = null;
		this.css(this.modalDivE,{display: 'none', visibility: 'hidden'});
		this.css(this.modalDivI,{display: 'none', visibility: 'hidden'});
	},
	
	/**
	 * Evento chamado quando a janela deve ser redimensionada.
	 *
	 * @author Dimas Melo Filho
	 */
	modalResize: function (event, obj) {
		var dim = def.windowDimension();
		def.css(
			def.modalDivE,
			{
				width: dim.width + 'px', 
				height: dim.height + 'px'
			}
		);
		def.css(
			def.modalDivI,
			{
				left: ((dim.width / 2) - (def.modalDivI.clientWidth/2)) + 'px', 
				top: ((dim.height / 2) - (def.modalDivI.clientHeight / 2)) + 'px'
			}
		);		
	},
	
	/**
	 * Evento chamado no onmousedown do div modal para bloquear cliques na p�gina.
	 *
	 * @author Dimas Melo Filho
	 */
	modalBlockMouse: function(event, obj) {
		return false;
	},
	
	/**
	 * Pega as dimens�es da janela.
	 *
	 * @author Dimas Melo Filho
	 * @return Objeto com as propriedades width e height.
	 */
	windowDimension: function() {
		var widthArray = [
			window.clientWidth || 0,
			document.documentElement.clientWidth || 0,
			document.body.clientWidth || 0].sort();
		var heightArray = [
			window.clientHeight || 0,
			document.documentElement.clientHeight || 0,
			document.body.clientHeight || 0].sort();
		while (widthArray.length > 0 && widthArray[0] <= 0) widthArray.shift();
		while (heightArray.length > 0 && heightArray[0] <= 0) heightArray.shift();
		if (widthArray.length == 0 || heightArray.lenght) throw "def.windowDimension n�o conseguiu encontrar o tamanho da janela.";
		return {width: widthArray[0], height: heightArray[0]};		
	},
	
	
	/*******************************************************************
	 * Fun��es de Bot�o
	 *
	 */
	
	/**
	 * Fun��o chamada quando o mouse est� em cima de um bot�o.
	 *
	 * @author Dimas Melo Filho
	 * @param  btn Elemento bot�o que o mouse est� em cima (onmouseover).
	 */
	buttonOver: function(btn) {
		while (btn != null && !(btn.className == 'btn_c')) btn = btn.parentNode;
		if (btn != null) {
			btn.className = "btn_cs";
			btn = btn.firstChild;
			btn.className = "btn_es";
			btn = btn.firstChild;
			btn.className = "btn_ds";
		}
	},
	
	/**
	 * Fun��o chamada quando o mouse sai de cima de um bot�o.
	 *
	 * @author Dimas Melo Filho
	 * @param  btn Elemento bot�o que o mouse sai de cima (onmouseout).
	 */
	buttonOut: function(btn) {
		while (btn != null && !(btn.className == 'btn_cs')) btn = btn.parentNode;
		if (btn != null) {
			btn.className = "btn_c";
			btn = btn.firstChild;
			btn.className = "btn_e";
			btn = btn.firstChild;
			btn.className = "btn_d";
		}
	},
	
	/*******************************************************************
	 * Fun��es Gen�ricas
	 *
	 */
	
	/**
	 * Definir v�rios estilos de uma s� vez.
	 *
	 * @author Dimas Melo Filho
	 * @param  obj Objeto que vai receber os estilos.
	 * @param  styles Objeto (chave: valor) com estilos para setar.
	 */
	css: function(obj, styles) {
		try {
			for (var s in styles) obj.style[s] = styles[s];
		} catch (e) {}
	},
	
	/**
	 * Adiciona uma classe �s classes do elemento.
	 *
	 * @author Dimas Melo Filho
	 * @param  el elemento para adicionar a classe.
	 * @param  clName Nome da classe para adicionar.
	 */
	addClass: function(el, clName) {
		var cls = el.className.split(" ");
		if (cls.indexOf(clName) == -1) {
			cls.push(clName);
			el.className = cls.join(" ");
		}
	},

	/**
	 * Remove uma classe das classes do elemento.
	 *
	 * @author Dimas Melo Filho
	 * @param  el elemento para remover a classe.
	 * @param  clName Nome da classe para remover.
	 */
	removeClass: function(el, clName) {
		var cls = el.className.split(" ");
		var clsIdx;
		while ( (clsIdx = cls.indexOf(clName)) > -1 )
			cls.splice(clsIdx,1);
		el.className = cls.join(" ");		
	},
	
	/**
	 * Encontra um elemento descendente pela Tag.
	 *
	 * @author Dimas Melo Filho.
	 * @param  el Elemento para procurar em seus subelementos.
	 * @param  tagName Nome da Tag.
	 * @return Primeiro elemento descendente encontrado.
	 */
	findFirst: function(el, tagName) {
		tagName = tagName.toLowerCase();
		el.findQueue = [el];
		while (el.findQueue.length > 0) {
			var e = el.findQueue.shift();
			if (e.tagName != null && e.tagName.toLowerCase() == tagName) return e;
			if (e.childNodes != null) for (var i = 0; i < e.childNodes.length; ) el.findQueue.push(e.childNodes[i++]);
		}
		delete el.findQueue;
		return null;
	},
	
	findNext: function(el, tagName) {
		tagName = tagName.toLowerCase();
		while (el.findQueue.length > 0) {
			var e = el.findQueue.shift();
			if (e.tagName != null && e.tagName.toLowerCase() == tagName) return e;
			if (e.childNodes != null) for (var i = 0; i < e.childNodes.length; ) el.findQueue.push(e.childNodes[i++]);
		}
		delete el.findQueue;
		return null;
	},
	
	/**
	 * Encontra um elemento descendente pela Tag e n�o salva a pesquisa para continuar com o findNext.
	 *
	 * @author Dimas Melo Filho
	 * @param  el Elemento para procurar em seus subelementos.
	 * @param  tagName Nome da Tag.
	 * @return Primeiro elemento descendente encontrado.
	 */
	findOnly: function(el, tagName) {
		tagName = tagName.toLowerCase();
		el.findQueue = [el];
		while (el.findQueue.length > 0) {
			var e = el.findQueue.shift();
			if (e.tagName != null && e.tagName.toLowerCase() == tagName) {
				delete el.findQueue;
				return e;
			}
			if (e.childNodes != null) for (var i = 0; i < e.childNodes.length; ) el.findQueue.push(e.childNodes[i++]);
		}
		delete el.findQueue;
		return null;
	},
	
	/**
	 * Encontra a posi��o absoluta de um elemento.
	 */
	elementOffset: function(el) {
		var t = 0;
		var l = 0;
		var w = el.offsetWidth;
		var h = el.offsetHeight;
		while (el != null) {
			t += (typeof(el.offsetTop) == "number" ? el.offsetTop : 0);
			l += (typeof(el.offsetLeft) == "number" ? el.offsetLeft : 0);
			el = el.offsetParent;
		}
		return {top: t, left: l, width: w, height: h};
	}
};
