/**
 * Classe para gerenciar Scrolling
 *
 * por Dimas Melo Filho (2012)
 */

/***
 * Define o objeto ai caso ele n�o esteja definido
 */ 
if (typeof(ai) != 'object') ai = function() {};

/***
 * Defini��o do Objeto scroll
 */
if (typeof(ai.scroll) != 'object') ai.scroll = function() {};

/***
 * Retorna a por��o vertical vis�vel de um elemento.
 *
 * @author Dimas Melo Filho
 * @date 2012-07-20
 * @param el: elemento.
 * @return number: por��o vertical vis�vel em pixels.
 */
ai.scroll.visibleHeight = function (el) {
	return (el.innerHeight || el.clientHeight);
}

/***
 * Retorna a por��o horizontal vis�vel de um elemento.
 *
 * @author Dimas Melo Filho
 * @date 2012-07-20
 * @param el: elemento.
 * @return number: por��o horizontal vis�vel em pixels.
 */
ai.scroll.visibleWidth = function (el) {
	return (el.innerWidth || el.clientWidth);
}

/***
 * Retorna a posi��o horizontal de rolagem de um elemento.
 *
 * @author Dimas Melo Filho
 * @date 2012-07-20
 * @param el: elemento.
 * @return number: posi��o horizontal de rolagem em pixels.
 */
ai.scroll.x = function (el) {
	return (el.pageXOffset || el.scrollLeft);
}

/***
 * Retorna a posi��o vertical de rolagem de um elemento.
 *
 * @author Dimas Melo Filho
 * @date 2012-07-20
 * @param el: elemento.
 * @return number: posi��o vertical de rolagem em pixels.
 */
ai.scroll.y = function (el) {
	return (el.pageYOffset || el.scrollTop);
}

/***
 * Retorna o ret�ngulo vis�vel de um elemento.
 *
 * @author Dimas Melo Filho
 * @date 2012-07-20
 * @param el: elemento.
 * @return retangulo vis�vel {x1, y1, x2, y2}.
 */
ai.scroll.visibleRect = function(el) {
	if (typeof(el) != 'object') throw 'scroll.visibleRect: elemento inv�lido.';
	var minX = ai.scroll.x(el);
	var minY = ai.scroll.y(el);
	var vr = {
		x1: minX,
		y1: minY,
		x2: minX + ai.scroll.visibleWidth(el),
		y2: minY + ai.scroll.visibleHeight(el)
	}
	for (val in vr) if (typeof(vr[val]) != 'number') throw 'scroll.visibleRect: n�o parece ser um elemento DOM.';
	return vr;
}

/***
 * Retorna a posi��o relativa de um elemento dentro de um container.
 *
 * @author Dimas Melo Filho
 * @date 2012-07-20
 * @param el: elemento que est� dentro do container.
 * @param container: container que cont�m o elemento.
 * @return ponto {x: posi��o horizontal, y: posi��o vertical}
 */
ai.scroll.relativePos = function(el, container) {
	if (el != null) {
		if (container == null) container = el.parentNode;
		var elX = 0;
		var elY = 0;
		var elW = el.clientWidth;
		var elH = el.clientHeight;
		if (typeof(el) != 'object' || typeof(container) != 'object') throw 'scroll.relativePos: O elemento ou container n�o s�o v�lidos.';
		do {
			elX += el.offsetLeft;
			elY += el.offsetTop;
			el = el.parentNode;
		} while (el != null && el != container);
		if (typeof(el) != 'object') throw 'scroll.relativePos: O elemento n�o pertence ao container.';
		return { x: elX, y: elY, w: elW, h: elH };
	} else {
		return {x:0,y:0,w:0,h:0};
	}
}

/***
 * Retorna o �ndice de visibilidade de um elementro dentro de outro.
 * 0 � totalmente oculto.
 * 4 � totalmente vis�vel.
 * � poss�vel fornecer o elemento (el) e o container (container) ou fornecer diretamente a posi��o 
 * do elemento (elpos) e o ret�ngulo vis�vel do container (vr).
 *
 * @author Dimas Melo Filho
 * @date 2012-07-20
 * @param el: elemento que est� dentro do container.
 * @param container: container que cont�m o elemento.
 * @param elpos: posi��o do elemento dentro do container.
 * @param vrect: ret�ngulo vis�vel do container.
 * @return number: valor de 0 a 4 que representa a visibilidade do elemento dentro do container, 
 *         onde 0 � completamente oculto e 4 � completamente vis�vel.
 */
ai.scroll.elementVisible = function(el, container, elpos, vrect) {
	if (elpos == null) elpos = ai.scroll.relativePos(el,container);
	if (elpos == null) return 4;
	if (vrect == null) vrect = ai.scroll.visibleRect(container);
	if (vrect == null) return 4;
	var vis = 0;
	if (vrect.x1 <= elpos.x) vis++;
	if (vrect.y1 <= elpos.y) vis++;
	if (vrect.x2 >= (elpos.x + elpos.w)) vis++;
	if (vrect.y2 >= (elpos.y + elpos.h)) vis++;
	return vis; // retorne um �ndice de visibilidade onde 0 � totalmente oculto e 4 � totalmente vis�vel.
}

/***
 * Faz scroll em um container de forma que torne um elemento filho vis�vel.
 *
 * @author Dimas Melo Filho
 * @date 2012-07-20
 * @param el: elemento filho (que est� dentro do container).
 * @param container: container que cont�m o elemento filho.
 * @param halign: alinhamento horizontal do elemento filho dentro do container. (opcional)
 *        pode ser: none, left, center, right.
 * @param valign: alinhamento vertical do elemento filho dentro do container. (opcional)
 *        pode ser: none, top, center, bottom.
 * @return null
 */
ai.scroll.makeVisible = function (el, container, halign, valign) {
	var elpos = ai.scroll.relativePos(el,container);
	var vrect = ai.scroll.visibleRect(container);
	if (ai.scroll.elementVisible(el, container, elpos, vrect) < 4) {
		halign = (typeof(halign) != 'string' ? 'none' : halign.toLowerCase());
		valign = (typeof(valign) != 'string' ? 'none' : valign.toLowerCase());
		var toX, toY, w = (vrect.x2-vrect.x1), h = (vrect.y2-vrect.y1);
		switch (halign) {
			case 'left': toX = elpos.x; break;
			case 'right': toX = elpos.x - w + elpos.w;  break;
			case 'center': toX = elpos.x - ((w - elpos.w) / 2);  break;
			default: toX = vrect.x1;  break;
		}
		switch (valign) {
			case 'top': toY = elpos.y; break;
			case 'bottom': toY = elpos.y - h + elpos.h;  break;
			case 'center': toY = elpos.y - ((h - elpos.h) / 2);  break;
			default: toY = vrect.y1;  break;
		}
		ai.scroll.to(container, toX, toY);
	}
}

/***
 * Faz scroll em um elemento para a posi��o determinada.
 *
 * @author Dimas Melo Filho
 * @date 2012-07-20
 * @param el: elemento filho (que est� dentro do container).
 * @param x: posi��o horizontal para rolar.
 * @param y: posi��o vertical para rolar.
 * @return null
 */
ai.scroll.to = function(el, x, y) {
	if (typeof(el) == 'object') {
		el.scrollLeft = x;
		el.scrollTop = y;
	}
}
