<?php
/**
 * Description of AplicacaoController
 *
 * @author victor
 */
class Clinica_AplicacaoController extends Zend_Controller_Action {

    private $_usuario;
    private $_modelAplicacao;
    private $_modelModulo;
    private $_modelAplicacaoModulo;
    
    public function init() {
        parent::init();
        
        $this->_modelAplicacao = new Aplicacao();
        $this->_modelModulo = new Modulo();
        $this->_modelAplicacaoModulo = new AplicacaoModulo();
        
        if (!Usuario::isLogged()) {
            $this->_forward("index");
        } else {
            $this->_usuario = Usuario::isLogged();
            $this->view->usuario = $this->_usuario;
        }
    }

    public function aplicacaoAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
        $modulos = $this->_modelModulo->fetchAll();
        $this->view->modulos = $modulos;
    }
    
    public function cadastrarAction() {
        $dados = $this->_request->getPost();
        
        if (!$dados['aplicacaoSubmit']) {
            $result = $this->_modelAplicacao->inserir($dados);
        } else {
            $result = $this->_modelAplicacao->editar($dados);
        }
        
        if ($result) {
            echo "ok";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function listarAction() {
        $aplicacao = $this->_request->getPost("aplicacao");
        $input = $this->_request->getPost("input");
        
        $result = $this->_modelAplicacao->listarAutocomplete($aplicacao);
        
        if ($result) {
            $html = "<table id='div_".$input."' style='width: 100%;' class='listagem' cellpadding='0' cellspacing='0' border='0'>";
            $cont = 0;
            foreach ($result as $r) {
                $color = ($cont % 2 == 0) ? "bgcolor='#c4c4c4'" : "" ;
                $html .= "<tr $color style='height: 25px;'>
                            <td id='aplicacao_".$input."' aplicacao='" . $r['codAplicacao'] . "'>";
                
                $html .= $r['nomeAplicacao'];
                
                $html .= "  
                            </td>
                            <td width='30px' id='excluir_".$input."'>
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
        $aplicacao = $this->_request->getPost("idAplicacao");
        $result = $this->_modelAplicacao->buscar($aplicacao);
        
        if ($result) {            
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function buscaraplicacaomoduloAction() {
        $aplicacao = $this->_request->getPost("idAplicacao");
        $result = $this->_modelAplicacaoModulo->buscarAplicacao($aplicacao);
        
        if ($result) {            
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function excluiracaoAction() {
        $aplicacao = $this->_request->getPost("idAplicacao");
        
        $result = $this->_modelAplicacao->excluir($aplicacao);
        
        if ($result) {            
            echo "Ação excluída com sucesso!";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
}