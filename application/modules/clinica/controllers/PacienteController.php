<?php
class Clinica_PacienteController extends Zend_Controller_Action {
    
    private $_usuario;
    private $_modelAcao;
    
    public function init() {
        parent::init();
        
        $this->_modelAcao = new ModuloAcao();
        
        if (!Usuario::isLogged()) {
            $this->_forward("index");
        } else {
            $this->_usuario = Usuario::isLogged();
            $this->view->usuario = $this->_usuario;
        }
    }
    
    public function pacienteAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
    }
}

