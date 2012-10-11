<?php
/**
 * Description of AcaoController
 *
 * @author victor
 */
class Clinica_AcaoController extends Zend_Controller_Action {

    private $_usuario;
    private $_modelAcao;
    
    public function init() {
        parent::init();
        
        $this->_modelAcao = new Acao();
        
        if (!Usuario::isLogged()) {
            $this->_forward("index");
        } else {
            $this->_usuario = Usuario::isLogged();
            $this->view->usuario = $this->_usuario;
        }
    }

    public function acaoAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
    }
    
    public function cadastrarAction() {
        $dados = $this->_request->getPost();
        
        if (!$dados['acaoSubmit']) {
            $result = $this->_modelAcao->inserir($dados);
        } else {
            $result = $this->_modelAcao->editar($dados);
        }
        
        
        if ($result) {
            echo "ok";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function listarAction() {
        $acao = $this->_request->getPost("acao");
        $input = $this->_request->getPost("input");
        
        $result = $this->_modelAcao->listarAutocomplete($acao);
        
        if ($result) {
            $html = "<table id='div_".$input."' style='width: 100%;' class='listagem' cellpadding='0' cellspacing='0' border='0'>";
            $cont = 0;
            foreach ($result as $r) {
                $color = ($cont % 2 == 0) ? "bgcolor='#c4c4c4'" : "" ;
                $html .= "<tr $color style='height: 25px;'>
                            <td id='acao_".$input."' acao='" . $r['codAcao'] . "'>";
                
                $html .= $r['nomeAcao'];
                
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
    
    public function buscaracaoAction() {
        $acao = $this->_request->getPost("idAcao");
        $result = $this->_modelAcao->buscar($acao);
        
        if ($result) {            
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function excluiracaoAction() {
        $acao = $this->_request->getPost("idAcao");
        
        $result = $this->_modelAcao->excluir($acao);
        
        if ($result) {            
            echo "Ação excluída com sucesso!";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
}