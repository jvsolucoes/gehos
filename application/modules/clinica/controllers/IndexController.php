<?php

class Clinica_IndexController extends Zend_Controller_Action {

    private $_usuario;
    
    public function init() {
        parent::init();
        
//        if (!Usuario::isLogged()) {
//            $this->_forward("index");
//        } else {
//            $this->_usuario = Usuario::isLogged();
//            $this->view->usuario = $this->_usuario;
//        }
    }
    
    public function isLogged() {
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_forward("login");
        }
    }

    public function indexAction() {
        $this->_forward("main");
    }
    
    public function loginAction() {
        $this->view->aplicacao = $this->_getAllParams();
        
        // Verifica se foi submetido via POST
        if (!$this->_request->isPost())
            return false;
        
        // Obtém os dados passados via POST
        $data = $this->_request->getPost();
        
        $db_adapter = Usuario::getDefaultAdapter();

        // Cria uma instancia de Zend_Auth
        $objAuth = Zend_Auth::getInstance();

        $auth_adapter = new Zend_Auth_Adapter_DbTable($db_adapter, 'usuario1', 'loginUsuario', 'hashSenhaUsuario', 'sha1(?)');
        
        // Configura as credencias informadas pelo usuário
        $auth_adapter->setIdentity($data['user']);
        $auth_adapter->setCredential($data['pass']);
        
        // Tenta autenticar o usuário
        $result = $objAuth->authenticate($auth_adapter);

        /**
         * Se o usuário for autenticado redireciona para a index e grava seu email,
         * caso contrário exibe uma mensagem de alerta na página
         */
        
        if ($result->isValid()) {
            
            /**
             * Pega os dados do usuário, omitindo a senha
             * http://framework.zend.com/manual/en/zend.auth.adapter.dbtable.html
             */
            $authData = $auth_adapter->getResultRowObject(null, 'hashSenhaUsuario');
            
            // Armazena os dados do usuário
            $objAuth->getStorage()->write($authData);
            
            $this->_redirect("/clinica/index/main");
            
        } else {
            $this->view->mensagem = 'Os dados informados (usuario e/ou senha) n&atilde;o s&atilde;o v&aacute;lidos.';
        }
    }

    public function logoutAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $objAuth = Zend_Auth::getInstance();

        // Limpa a autenticação
        $objAuth->clearIdentity();
        $this->_redirect("/clinica");
    }
    
    public function mainAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->isLogged();
        
    }
}

