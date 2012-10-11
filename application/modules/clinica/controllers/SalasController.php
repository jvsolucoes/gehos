<?php
/**
 * Description of SalasController
 *
 * @author victor
 */
class Clinica_SalasController extends Zend_Controller_Action {

    private $_usuario;
    private $_modelSala;
    
    public function init() {
        parent::init();
        
        $this->_modelSala = new Sala();
        
        if (!Usuario::isLogged()) {
            $this->_forward("index");
        } else {
            $this->_usuario = Usuario::isLogged();
            $this->view->usuario = $this->_usuario;
        }
    }

    public function salasAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
    }
    
    public function cadastrarAction() {
        $dados = $this->_request->getPost();
        
        if (!$dados['salaSubmit']) {
            $result = $this->_modelSala->inserir($dados);
        } else {
            $result = $this->_modelSala->editar($dados);
        }
        
        if ($result) {
            echo "ok";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function listarAction() {
        $unidade = $this->_request->getPost("unidade");
        $sala = $this->_request->getPost("sala");
        $input = $this->_request->getPost("input");
        
        $result = $this->_modelSala->listarAutocomplete($unidade, $sala);
        
        if ($result) {
            $html = "<table id='div_".$input."' style='width: 100%;' class='listagem' cellpadding='0' cellspacing='0' border='0'>";
            $cont = 0;
            foreach ($result as $r) {
                $color = ($cont % 2 == 0) ? "bgcolor='#c4c4c4'" : "" ;
                $html .= "<tr $color style='height: 25px;'>
                            <td id='sala_".$input."' sala='" . $r['codUnidadeSala'] . "'>";
                
                $html .= $r['nomeSala'];
                
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
        $sala = $this->_request->getPost("sala");
        $result = $this->_modelSala->buscar($sala);
        
        if ($result) {            
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function excluirAction() {
        $acao = $this->_request->getPost("idAcao");
        
        $result = $this->_modelSala->excluir($acao);
        
        if ($result) {            
            echo "Ação excluída com sucesso!";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
}