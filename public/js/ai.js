/**
 * Classe principal da biblioteca ai
 *
 * por Dimas Melo Filho
 */

/***
 * Define o objeto ai caso ele não esteja definido
 */ 
if (typeof(ai) != 'object') ai = function() {};

/***
 * Definição da classe ai.include.
 */
if (typeof(ai.loader != 'object')) ai.include = function() {};

/***
 * Inicializa a classe include.
 *
 * @author Dimas Melo Filho
 * @date 2012-07-24
 */
ai.include.init = function() {
	if (ai.include.head == null) {
		ai.include.head = document.getElementsByTagName("head")[0];
		if (ai.include.head == null) throw "ai.include: Não foi possível encontrar o cabeçalho da página.";
		ai.include.loadJsList();
		ai.include.loadCssList();
	}
}

/***
 * Obtém a lista de arquivos carregados.
 *
 * @author Dimas Melo Filho
 * @date 2012-07-24
 */
ai.include.loadList = function() {
	var el, eList = [ai.include.head];
	ai.include.jsList = [];
	ai.include.cssList = [];
	while (eList.length > 0) {
		el = eList.pop();
		eList = eList.concat(el.children);
		if (el == null) break;
		var tag = el.tagName.toLowerCase()
		if (tag == 'script') {
			var src = el.getAttribute('src');
			if (src != null) ai.include.jsList.push(src);
		}
		else if (tag == 'link') {
			var src = el.getAttribute('href');
			if (src != null && el.getAttribute('type').toLowerCase() == "text/css") {
				ai.include.cssList.push(src);
			}
		}
	}
}

/***
 * Verifica se o script já foi incluído.
 *
 * @author Dimas Melo Filho
 * @date 2012-07-24
 */
ai.include.includedJs = function(file) {
	for (var i=0; i<ai.include.jsList.length; i++)
		if (ai.include.jsList[i] == file) return true;
	return false;
}

/***
 * Verifica se o css já foi incluído.
 *
 * @author Dimas Melo Filho
 * @date 2012-07-24
 */
ai.include.includedCss = function(file) {
	for (var i=0; i<ai.include.cssList.length; i++)
		if (ai.include.cssList[i] == file) return true;
	return false;
}

/*** 
 * Método para incluir um arquivo javascript
 *
 * @author Dimas Melo Filho
 * @date 2012-07-24
 */
ai.include.js = function(file) {
	ai.include.init();
	if (!ai.include.includedJs(file)) {
		var script = document.createElement('script');
		script.setAttribute('type', 'text/javascript');
		script.setAttribute('src', file);
		ai.include.head.appendChild(script);
		ai.include.jsList.push(file);
	}
}

/*** 
 * Método para incluir um arquivo css
 *
 * @author Dimas Melo Filho
 * @date 2012-07-24
 */
ai.include.css = function(file) {
	ai.include.init();
	if (!ai.include.includedCss(file)) {
		var css = document.createElement('link');
		css.setAttribute('type', 'text/css');
		css.setAttribute('rel','stylesheet');
		css.setAttribute('rev','stylesheet');
		css.setAttribute('href', file);
		ai.include.head.appendChild(css);
		ai.include.cssList.push(file);
	}
}
