<?php
/**
 * Description of AtendimentoController
 *
 * @author victor
 */
class Clinica_AtendimentoController extends Zend_Controller_Action {

    private $_usuario;
    
    public function init() {
        parent::init();
        
        if (!Usuario::isLogged()) {
            $this->_forward("index");
        } else {
            $this->_usuario = Usuario::isLogged();
            $this->view->usuario = $this->_usuario;
        }
    }

    public function atendimentoAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();

    }
    
}