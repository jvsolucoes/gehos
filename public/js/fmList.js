/**
 * Componente de Lista.
 *
 * Para personalizar a tabela, utilize estilos CSS para a classe fmList
 * Para personalizar as cores das linhas pares e impares altere os estilos das classes fmList0 e 
 * fmList1.
 *
 * @author Dimas Melo Filho
 */
 
/**
 * Construtor
 *
 * @author Dimas Melo Filho
 * @param  tbl Elemento table onde funcionar� a lista.
 * @param  options Objeto com os pares "op��o / valor".
 */
function fmList(tbl, options, tabId) {
	/* Valida��o de par�metros */
	if (options == null) options = [];
	
	/* Atributos P�blicos */
	this.table = tbl;
	this.tabId = tabId;
	
	/* Construtor */
    this.tableBody();
	this.setOptions(options);
	this.setClasses();
}

/* M�todos P�blicos */

/**
 * Atribui todas as op��es do usu�rio.
 *
 * @author Dimas Melo Filho
 * @param  opt Objeto com os pares "op��o / valor".
 */
fmList.prototype.setOptions = function(opt) {
	var validOptions = []; 																			// Lista de op��es v�lidas
	if (opt != null) 
		for (o in opt)
			if (validOptions.indexOf(o) > -1)
				this[o] = opt[o];
}

/**
 * Atribui as classes a todos os elementos da tabela
 *
 * @author Dimas Melo Filho
 */
fmList.prototype.setClasses = function () {
	def.addClass(this.table, 'fmList');
	var row = def.findFirst(this.table, 'tr');
	this.recolorLines(0);
}

/**
 * Encontra e salva o corpo da tabela
 *
 * @author Dimas Melo Filho
 * @return O elemento tbody (corpo da tabela).
 */
fmList.prototype.tableBody = function () {
	this.tbody = def.findOnly(this.table, 'tbody');
}

/**
 * Adiciona uma linha na lista.
 *
 * @author Dimas Melo Filho
 * @param  rowData Array com as colunas da linha.
 * @param  hasId Indica se as linhas devem possuir o ID enviado na primeira coluna do resultado para remoção automática.
 */
fmList.prototype.addRow = function (rowData, hasId) {
	if (hasId == null) hasId = true;
	var tr = document.createElement('tr');
	tr.setAttribute("id","fmList" + (hasId ? rowData[0] : this.rowNum) + "_" + this.tabId);
	var colClass = "fmList" + (this.rowNum++ % 2);
	for (var i = 0; i < rowData.length; i++) {
		var td = document.createElement('td');
		td.className = colClass;
		if (rowData[i] == "<B>") {
			td.innerHTML = '<div class="btnDel" onclick="cadastro' + this.tabId + '.remove(' + rowData[0] + ',\'fmList' + rowData[0] + '_' + this.tabId + '\')"></div>';
		}
		else {
			td.innerHTML = rowData[i];
		}
		tr.appendChild(td);
	}
	var td = document.createElement('td');
	td.className = colClass;
	this.tbody.appendChild(tr);
}

/**
 * Remove uma linha da lista.
 *
 * @author Dimas Melo Filho
 * @param  rowNumber N�mero da linha para remover.
 */
fmList.prototype.removeRow = function (rowNumber) {
	if (rowNumber >= this.tbody.childNodes.length) return;
	delete this.tbody.removeChild(this.tbody.childNodes[rowNumber]);
	this.recolorLines(rowNumber);
}

/**
 * Remove uma linha da lista.
 *
 * @author Dimas Melo Filho
 * @param  rowEl Elemento TR a ser removido
 */
fmList.prototype.removeRowElement = function (rowEl) {
	delete this.tbody.removeChild(rowEl);
	this.recolorLines(0);
}

/**
 * Recolore as linhas a partir de certa linha.
 *
 * @author Dimas Melo Filho
 * @param  rowStart Linha a partir da qual a lista deve ser recolorida.
 */
fmList.prototype.recolorLines = function (rowStart) {
	while (rowStart < this.tbody.childNodes.length) {
		var row = this.tbody.childNodes[rowStart];
		var colClass = "fmList" + (rowStart++ % 2);
		var column = def.findFirst(row, 'td');
		while (column != null) {
			column.className = colClass;
			column = def.findNext(row, 'td');
		}
	}
	this.rowNum = rowStart;
}

/**
 * Limpa todas as linhas da lista.
 *
 * @author Dimas Melo Filho
 */
fmList.prototype.clear = function () {
	this.rowNum = 0;
	this.tbody.innerHTML = '';
}