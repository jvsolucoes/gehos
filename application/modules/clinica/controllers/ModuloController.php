<?php
/**
 * Description of ModuloController
 *
 * @author victor
 */
class Clinica_ModuloController extends Zend_Controller_Action {

    private $_usuario;
    private $_modelModulo;
    private $_modelAcao;
    private $_modelModuloAcao;
    
    public function init() {
        parent::init();
        
        $this->_modelModulo = new Modulo();
        $this->_modelAcao = new Acao();
        $this->_modelModuloAcao = new ModuloAcao();
        
        if (!Usuario::isLogged()) {
            $this->_forward("index");
        } else {
            $this->_usuario = Usuario::isLogged();
            $this->view->usuario = $this->_usuario;
        }
    }

    public function moduloAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
        $acoes = $this->_modelAcao->fetchAll(null, "nomeAcao ASC");
        $this->view->acoes = $acoes;
        $modulos = $this->_modelModulo->fetchAll(null, "nomeModulo ASC");
        $this->view->modulos = $modulos;
    }
    
    public function cadastrarAction() {
        $dados = $this->_request->getPost();
        
        if (!$dados['moduloSubmit']) {
            $result = $this->_modelModulo->inserir($dados);
        } else {
            $result = $this->_modelModulo->editar($dados);
        }
        
        if ($result) {
            echo "ok";
        } else {
            echo "naopassou";
        }
        die();
    }
    
    public function listarAction() {
        $modulo = $this->_request->getPost("modulo");
        $input = $this->_request->getPost("input");
        
        $result = $this->_modelModulo->listarAutocomplete($modulo);
        
        if ($result) {
            $html = "<table id='div_".$input."' style='width: 100%;' class='listagem' cellpadding='0' cellspacing='0' border='0'>";
            $cont = 0;
            foreach ($result as $r) {
                $color = ($cont % 2 == 0) ? "bgcolor='#c4c4c4'" : "" ;
                $html .= "<tr $color style='height: 25px;'>
                            <td id='modulo_".$input."' modulo='" . $r['codModulo'] . "'>";
                
                $html .= $r['nomeModulo'];
                
                $html .= "  
                            </td>
                            <td width='30px' id='excluir_".$input."' modulo='" . $r['codModulo'] . "'>
                                <img src='" . dirname($_SERVER['PHP_SELF']) . "/img/excluir_32x32.png' alt='' width='20px' />
                            </td>
                        </tr>";
                $cont++;
            }
                            
            $html .= "</table>";
            
            echo $html;
        } else {
            echo "<div id='div_".$input."'>Não existe registro para essa pesquisa</div>";
        }
        
        die();
    }
    
    public function buscarAction() {
        $modulo = $this->_request->getPost("idModulo");
        $result = $this->_modelModulo->buscar($modulo);
        
        if ($result) {            
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function buscarmoduloacaoAction() {
        $modulo = $this->_request->getPost("idModulo");
        $result = $this->_modelModuloAcao->buscarModulo($modulo);
        
        if ($result) {            
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function excluirAction() {
        $modulo = $this->_request->getPost("idModulo");
        $result = $this->_modelModulo->excluir($modulo);
        
        if ($result) {            
            echo "Módulo excluído com sucesso!";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
}