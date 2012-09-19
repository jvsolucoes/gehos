/*
	fmMenu - Módulo de Geração dinâmica de Menus.
	por Dimas Melo Filho
*/

fmMenuTimer = null;
fmMenuOwner = null;
fmSubMenuOwner = null;
fmSubSubMenuOwner = null;
fmMenuObj = null; 
fmMenuSubObj = null;
fmMenuSubSubObj = null;
/* Mostra um submenu */
function fmMenuShow(menuOwner, menuObj) {
    fmMenuHide();
    document.onclick = fmMenuHide;
    fmMenuSubHide();
    fmMenuOwner = $('#'+menuOwner);
    if (fmMenuOwner != null) fmMenuOwner.addClass('selMenu');
    var menuOffset = fmMenuOwner.offset();
    menuOffset.top += fmMenuOwner.height();
    fmMenuObj = menuObj;
    $('#'+menuObj).css({
        top: menuOffset.top, 
        left: menuOffset.left
    }).slideDown(400).show();
}
/* Mostra um subsubmenu */
function fmMenuSubShow(menuOwner, menuObj) {
    fmMenuSubHide();
    fmSubMenuOwner = $("#"+menuOwner);
    if (fmSubMenuOwner != null) fmSubMenuOwner.addClass('selSubMenu');
    var menuOffset = fmSubMenuOwner.offset();
    menuOffset.left += fmSubMenuOwner.width() + parseInt(fmSubMenuOwner.css("padding-left"),10) + parseInt(fmSubMenuOwner.css("padding-right"),10);
    fmMenuSubObj = menuObj;
    $("#"+menuObj).css({
        top: menuOffset.top, 
        left: menuOffset.left
    }).slideDown(400).show();
}
/* Mostra um subsubsubmenu */
function fmMenuSubSubShow(menuOwner, menuObj) {
    fmMenuSubHide();
    fmSubSubMenuOwner = $("#"+menuOwner);
    if (fmSubSubMenuOwner != null) fmSubSubMenuOwner.addClass('selSubMenu');
    var menuOffset = fmSubSubMenuOwner.offset();
    menuOffset.left += fmSubSubMenuOwner.width() + parseInt(fmSubSubMenuOwner.css("padding-left"),10) + parseInt(fmSubSubMenuOwner.css("padding-right"),10);
    fmMenuSubSubObj = menuObj;
    $("#"+menuObj).css({
        top: menuOffset.top, 
        left: menuOffset.left
    }).show();
}
/* Oculta um submenu e qualquer subsubmenu que esteja visível */
function fmMenuHide() {
    document.onclick = null;
    fmMenuSubHide();
    fmMenuClearTimer();
    if (fmMenuObj != null) {
        $('#'+fmMenuObj).hide();
        fmMenuObj = null;
    }
    if (fmMenuOwner != null) fmMenuOwner.removeClass('selMenu');
}
/* Oculta um subsubmenu */
function fmMenuSubHide() {
    fmMenuClearTimer();
    if (fmMenuSubObj != null) {
        $("#"+fmMenuSubObj).hide();
        fmMenuSubObj = null;
    }
    if (fmSubMenuOwner != null) fmSubMenuOwner.removeClass('selSubMenu');
}
/* Oculta um subsubmenu */
function fmMenuSubSubHide() {
    fmMenuClearTimer();
    if (fmMenuSubSubObj != null) {
        $("#"+fmMenuSubSubObj).hide();
        fmMenuSubSubObj = null;
    }
    if (fmSubSubMenuOwner != null) fmSubSubMenuOwner.removeClass('selSubMenu');
}
/* Inicia o timer para ocultar o menu por inatividade */
function fmMenuStartTimer() {
    fmMenuTimer = setTimeout('fmMenuHide();',700);
}

/* Inicia o timer para ocultar o menu por inatividade */
function fmSubSubMenuStartTimer() {
    fmMenuTimer = setTimeout('fmMenuHide();',700);
}
/* Cancela o timer para ocultar o menu por inatividade */
function fmMenuClearTimer() {
    if (fmMenuTimer != null) { 
        clearTimeout(fmMenuTimer);
        fmMenuTimer = null;
    }
}

menuItem = null;
navLevel = 0;

/* Função de navegação de menu para esquerda*/
function fmKeyNavLeft(event, obj) {
    switch (navLevel) {
        case 0:
            if (menuItem == null) menuItem = $(".menuItem").last();
            menuItem = menuItem.prev(".menuItem");
            if (menuItem.length <= 0) menuItem = $(".menuItem").last();
            break;
        case 1:
            menuItem.removeClass('selSubMenu');
            menuItem = fmMenuOwner.prev(".menuItem");
            if (menuItem.length <= 0) menuItem = $(".menuItem").last();
            navLevel = 0;
            break;	
        case 2:
            menuItem.removeClass('selSubMenu');
            menuItem = fmSubMenuOwner;
            navLevel = 1;
            break;	
    }
    eval($(menuItem).mouseover());
    return false;
}
/* Função de navegação de menu para direita*/
function fmKeyNavRight(event, obj) {
    switch (navLevel) {
        case 0:
            if (menuItem == null) menuItem = $(".menuItem").first();
            menuItem = menuItem.next(".menuItem");
            if (menuItem.length <= 0) menuItem = $(".menuItem").first();
            break;
        case 1:
            var nMenuItem = $("#"+fmMenuSubObj+" li").first();
            if (nMenuItem.length > 0) {
                menuItem = nMenuItem.addClass('selSubMenu');
                navLevel = 2;
                break;
            } // else sem break
        case 2:
            menuItem.removeClass('selSubMenu');
            menuItem = fmMenuOwner.next(".menuItem");
            if (menuItem.length <= 0) menuItem = $(".menuItem").first();
            navLevel = 0;
            break;			
    }
    eval($(menuItem).mouseover());
    return false;
}
/* Função de navegação de menu para cima*/
function fmKeyNavUp(event, obj) {
    switch (navLevel) {
        case 1: // SubMenu
            menuItem = menuItem.removeClass('selSubMenu').prev('li');
            if (menuItem.length <= 0) {
                menuItem = fmMenuOwner;
                navLevel = 0;
            }
            else menuItem.addClass('selSubMenu');
            break;
        case 2: //SubSubMenu
            var nMenuItem = menuItem.prev('li');
            if (nMenuItem.length > 0) {
                menuItem.removeClass('selSubMenu');
                menuItem = nMenuItem.addClass('selSubMenu');
            }
            break;
    }
    eval($(menuItem).mouseover());
    return false;
}
/* Função de navegação de menu para baixo*/
function fmKeyNavDown(event, obj) {
    switch (navLevel) {
        case 0: // Barra
            menuItem = $("#"+fmMenuObj+" li").first().addClass('selSubMenu');
            navLevel = 1;
            break;
        case 1: // SubMenu
            var nMenuItem = menuItem.next('li');
            if (nMenuItem.length > 0) {
                menuItem.removeClass('selSubMenu');
                menuItem = nMenuItem.addClass('selSubMenu');
            }
            break;
        case 2: //SubSubMenu
            var nMenuItem = menuItem.next('li');
            if (nMenuItem.length > 0) {
                menuItem.removeClass('selSubMenu');
                menuItem = nMenuItem.addClass('selSubMenu');
            }
            break;
    }
    eval($(menuItem).mouseover());
    return false;
}

function fmKeyNavEnter(event, obj) {
    eval($(menuItem).click());
    return false;
}

/* Registra os atalhos de teclado usando o framework */
def.addShortcut("ctrl+left", fmKeyNavLeft);
def.addShortcut("ctrl+right", fmKeyNavRight);
def.addShortcut("ctrl+up", fmKeyNavUp);
def.addShortcut("ctrl+down", fmKeyNavDown);
def.addShortcut("ctrl+enter", fmKeyNavEnter);