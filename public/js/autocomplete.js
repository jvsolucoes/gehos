/**
 * Autocomplete
 */
var fmAutocompleteSel;

 
/**
 * Inicializa o autocomplete em um campo.
 * 
 * @author Dimas Melo Filho
 * @param  field: campo para inicialização.
 * @param  url: url do autocomplete.
 * @param  minChars: quantidade mínima de caracteres necessária para ativar o autocomplete.
 * @param  template: template de design da caixa de resultados. Deve ser um objeto javascript no seguinte formato:
 *                   {bAll: "html", eAll: "html", bRow: "html", eRow: "html", bCol: "html", eCol: "html"}
 *                   o "html" em cada campo do template é o html que modela uma parte da caixa de resultados, considerando que:
 *					 bAll contém o html que aplicado ao começo da caixa.
 *					 eAll contém o html que aplicado ao fim da caixa.
 *					 bRow contém o html aplicado ao começo de cada linha de resultado.
 *					 eRow contém o html aplicado ao fim de cada linha de resultado.
 *					 bCol contém o html aplicado ao começo de cada coluna de resultado.
 *					 eCol contém o html aplicado ao fim de cada coluna de resultado.
 *					 o html de linha ou de coluna deve conter alguma chamada à função fmAutocomplete.selectItem(this,##,#%#), que
 *					 é responsável por selecionar o resultado quando o usuário aperta enter ou clica na função.
 * @param  selectFn: função que é invocada quando um item é selecionado (opcional).
 *         opção usada normalmente quando se quer setar mais de um campo com o mesmo autocomplete.
 * @param  pos: posição da caixa de resultados (pode ser 'top' ou 'bottom'). o padrão é 'bottom'.
 */

function fmAutocomplete(args) {
    /* Inicializar Propriedades */
    this.InitializeProperties(args);
    /* Criar div para mostrar os resultados */
    this.createResultBox();
    /* Inicializar handler de eventos */
    this.InitializeEvents();
    this.field.ac = this;
    this.editing = false;
    this.showing = false;
    this.navItem = 0;
    this.numItems = 0;
    this.initialValue = "";
    this.blurTimeout = null;
}

/**
 * Inicializar Propriedades da tabela.
 */
fmAutocomplete.prototype.InitializeProperties = function (args) {
    if (args == null) throw "Autocomplete: é necessário informar os parâmetros do autocomplete.";
    if (args.field == null) throw "Autocomplete: é necessário informar o componente que receberá o autocomplete.";
    else this.field = args.field;
    if (args.url == null) throw "Autocomplete: é necessário informar a URL do autocomplete.";
    else this.url = args.url;
    this.minChars = (args.minChars == null || args.minChars < 0) ? 3 : args.minChars;
    if (args.template == null) this.template = {
        bAll:"<table width=\'100%\' cellpadding=\'0\' cellspacing=\'0\'><tbody>",	/* HTML no início de tudo. */
        eAll:"</tbody></table>",													/* HTML ao final de tudo. */
        bRow:"<tr class=\'acLinha\' onclick=\'fmAutocomplete.selectItem(this,#r#,#v#,#d#)\'>",	/* HTML no início de cada linha de resultado. */
        eRow:"</tr>",																/* HTML no fim de cada linha de resultado. */
        bCol:"<td>",																/* HTML no início de cada coluna de resultado. */
        eCol:"</td>"																/* HTML no fim de cada coluna de resultado. */
    };
    else {
        this.template = args.template;
        var tFields = ["bAll","eAll","bRow","eRow","bCol","eCol"];
        for (var i=0; i<tFields.length; i++)
            if (typeof(this.template[tFields[i]]) != 'string') 
                this.template[tFields[i]] = "";
    }
    this.selectFn = typeof(args.selectFn) != 'function' ? null : args.selectFn;
    if (typeof(args.pos) == 'string') {
        switch (args.pos) {
            case 'top':
                this.pos = 1;
                break;
            case 'bottom':
            default:
                this.pos = 0;
        }
    } else {
        this.pos = 0;
    }
    this.params = typeof(args.params) != 'object' ? {} : args.params;
}

/**
 * Inicializa os eventos do campo para o autocomplete.
 *
 * @author Dimas Melo Filho
 * @date 2012-07-23
 */
fmAutocomplete.prototype.InitializeEvents = function() {
    var a = this;
    def.addEventListener(this.field,'onkeyup',function (evt, obj) {
        return a.keyup(evt, obj);
    });
    def.addEventListener(this.field,'onkeydown',function (evt, obj) {
        return a.keydown(evt, obj);
    });
    def.addEventListener(this.field,'onfocus',function (evt, obj) {
        return a.focus(evt, obj);
    });
    def.addEventListener(this.field,'onblur',function (evt, obj) {
        return a.blur(evt, obj);
    });
}

/**
 * Criar uma caixa para exibir os resultados do autocomplete.
 */
fmAutocomplete.prototype.createResultBox = function() {
    this.container = document.createElement('div');
    this.container.field = this.field;
    this.container.ac = this;
    def.css(this.container, {
        position: 'absolute', 
        display: 'none'
    });
    this.resultBox = document.createElement('div');
    this.resultBox.className = "fmAutocompleteBox";
    this.resultBox.field = this.field;
    this.resultBox.ac = this;
    def.addEventListener(this.resultBox,'onscroll',function (evt, obj) {
        obj.ac.field.focus();
    });
    def.css(this.resultBox, {
        position: 'relative', 
        display: 'none', 
        overflowX: 'hidden', 
        overflowY: 'scroll'
    });
    this.container.appendChild(this.resultBox);
    this.field.parentNode.appendChild(this.container);
}

/**
 * Callback executada quando o usuário digitou alguma tecla no campo de texto (após ter soltado a tecla). Evento OnKeyUp.
 *
 * @param event: dados do evento.
 * @param obj: campo que ativou o evento (o campo do autocomplete).
 */
fmAutocomplete.prototype.keyup = function(event, obj) {
    var k = event.which || event.keyCode || event.charCode;
    if (k == 27 || (event.ctrlKey && k == 88)) {
        this.hide();
        this.field.value = this.initialValue;
    } else if (obj.autocomplete != null && obj.value.length >= this.minChars) {
        if (k != 38 && k != 40 && k != 13) {
            this.query(obj.value.length > 0 ? obj.value : '%');
        }
    } else {
        this.hide();
    }
    return false;
}

/**
 * Callback executado quando o usuário pressiona uma tecla. Evento OnKeyDown.
 *
 * @author Dimas Melo Filho
 * @param  event: dados do evento.
 * @param  obj: o objeto que causou o evento (o campo do autocomplete).
 */
fmAutocomplete.prototype.keydown = function(event, obj) {
    var k = event.which || event.keyCode || event.charCode;
    if (!this.editing) {
        this.initialValue = this.field.value;
        this.editing = true;
    } 
    if (this.showing) {
        if (k == 38) {
            this.navUp();
            return false;
        } else if (k == 40) {
            this.navDown();
            return false;
        } else if (k == 13) {
            this.navSel();
            return true;
        } 
    }
    return true;
}

/**
 * Marca o item anterior da caixa de resultados para seleção.
 *
 * @author Dimas Melo Filho.
 */
fmAutocomplete.prototype.navUp = function() {
    this.navItem--;
    this.moveDown = false;
    if (this.navItem < 1) this.navItem = 1;
    this.updateNav();
}

/**
 * Marca o próximo item da caixa de resultados para seleção.
 *
 * @author Dimas Melo Filho
 */
fmAutocomplete.prototype.navDown = function() {
    this.navItem++;
    this.moveDown = true;
    if (this.navItem > this.numItems) this.navItem = this.numItems;
    this.updateNav();
}

/**
 * Seleciona o item marcado na caixa de resultados.
 *
 * @author Dimas Melo Filho
 */
fmAutocomplete.prototype.navSel = function() {
    $(this.resultBox).find('.acLinhaSel').removeClass('acLinhaSel').click();
}

/**
 * Atualiza a exibição do campo que está marcado (atualiza a classe CSS dos itens da caixa de resultados).
 *
 * @author Dimas Melo Filho
 */
fmAutocomplete.prototype.updateNav = function () {
    $(this.resultBox).find('.acLinhaSel').removeClass('acLinhaSel');
    var el = $(this.resultBox).find('.acLinha:nth-child(' + (this.navItem) + ')').addClass('acLinhaSel').get(0);
    ai.scroll.makeVisible(el, this.resultBox, 'left', (this.moveDown ? 'bottom' : 'top'));
}

/**
 * Função callback chamada quando o usuário seleciona o campo do autocomplete.
 *
 * @author	Dimas Melo Filho
 * @param 	event: evento onfocus
 * @param	obj: campo de texto que ativou o evento.
 */
fmAutocomplete.prototype.focus = function(event, obj) {
    if (this.blurTimeout != null) clearTimeout(this.blurTimeout);
    else if (this.minChars == 0) this.query('%');
    this.blurTimeout = null;
    fmAutocompleteSel = this;
    return true;
}

/**
 * Função callback chamada quando ocorre blur.
 */
fmAutocomplete.prototype.blur = function(event, obj) {
    var a = this;
    this.blurTimeout = setTimeout(function () {
        a.hide();
        a.blurTimeout = null;
    }, 500);
}


/**
 * Faz uma consulta de autocomplete.
 *
 * @author	Dimas Melo Filho
 * @param   q: Texto para enviar
 */
fmAutocomplete.prototype.query = function(q) {
    var a = this;
    var p = {
        "q" : q
    };
    for (x in this.params) {
        p[x] = eval(this.params[x]);
    }
    fmAjax(this.url, p, function (data) {
        a.queryResult(data);
    }, true);
}

/**
 * Processa os resultados e organiza os dados para exibicao.
 * Formato do JSON:
 * {
 *		res: [
 *			{ col: [col0, col1, col2, col3, ...] },
 *			{ col: [col0, col1, col2, col3, ...] },
 *			...
 *		]
 * }
 *
 * @author Dimas Melo Filho
 * @param data: dados recebidos.
 */
fmAutocomplete.prototype.queryResult = function(data) {
    var acData = JSON.parse(data);
    var text = this.template.bAll;
    for (var r=0; r < acData.res.length; r++) {
        var cods = JSON.stringify(acData.res[r].cod);
        var vals = JSON.stringify(acData.res[r].val);
        var cols = JSON.stringify(acData.res[r].col);
        text += this.template.bRow.replace('#r#',cods).replace('#v#',vals).replace('#d#',cols);
        for (var c=0; c < acData.res[r].col.length; c++) {
            text += this.template.bCol + acData.res[r].col[c] + this.template.eCol;
        }
        text += this.template.eRow.replace('#r#',cods).replace('#v#',vals).replace('#d#',cols);
    }
    text += this.template.eAll;
    if (acData.res.length == 0) text = '<div style="font-size: 8pt">Nenhum registro encontrado.</div>';
    this.numItems = acData.res.length;
    if (this.navItem > this.numItems) this.navItem = 1;
    if (this.navItem == 0) this.navItem = 1;
    this.resultBox.innerHTML = text;
    this.updateNav();
    this.show();
}

/**
 * Exibe a caixa de resultados.
 *
 * @author Dimas Melo Filho
 */
fmAutocomplete.prototype.show = function() {
    if (!this.showing) {
        var p = ai.scroll.relativePos(this.field);
        var t = (this.pos == 0 ? 0 : (-164-p.h));
        def.css(this.resultBox, {
            left: (5) + "px", 
            top: t + "px", 
            width: (p.w-10) + "px", 
            height: 160 + "px"
        });
        $(this.container).show();
        $(this.resultBox).show('fast');
        this.showing = true;
    }
}

/**
 * Oculta a caixa de resultados.
 *
 * @author Dimas Melo Filho
 */
fmAutocomplete.prototype.hide = function() {
    if (this.showing) {
        $(this.container).hide();
        $(this.resultBox).hide('fast');
        this.showing = false;
    }
}

/**
 * Seleciona um item.
 * 
 * @author Dimas Melo Filho
 * @param  real: valor real (que será usado no cadastro).
 * @param  val: valor que será exibido no autocomplete.
 */
fmAutocomplete.prototype.select = function(real, val, data) {
    this.field.realValue = real;
    this.field.value = val;
    if (typeof(this.selectFn) == "function") this.selectFn(real, val, data);
    this.editing = false;
    this.hide();
}

/**
 * Método estático para selecionar um item dentro de uma resultBox. Este método independe da instância do autocomplete.
 *
 * @author Dimas Melo Filho
 * @param  objEbt: objeto que ativou o evento (objeto que foi clicado na caixa de resultados).
 * @param  real: valor real (que será usado no cadastro).
 * @param  val: valor que será exibido no autocomplete.
 */
fmAutocomplete.selectItem = function(objEvt, real, val, data) {
    var el = objEvt;
    while (el != null && el.ac == null) el = el.parentNode;
    if (el == null) throw "Erro ao tentar selecionar um elemento do autocomplete. O elemento parece não estar em uma caixa de resultados.";
    el.ac.select(real,val,data);
}