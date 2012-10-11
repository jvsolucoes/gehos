<?php
class Clinica_TiposprofissionaisController extends Zend_Controller_Action {
    
    private $_usuario;
    private $_modelTipoProfissional;
    private $_modelSubTipoProfissional;
    
    
    public function init() {
        parent::init();
        
        $this->_modelTipoProfissional = new TipoProfissional();
        $this->_modelSubTipoProfissional = new SubTipoProfissional();
        
        if (!Usuario::isLogged()) {
            $this->_forward("index");            
        } else {
            $this->_usuario = Usuario::isLogged();
            $this->view->usuario = $this->_usuario;
        }
    }
    
    public function tiposprofissionaisAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
        
    }
    
//    public function subtipoprofissionalAction(){
//        $this->view->aplicacao = $this->_getAllParams();
//        $this->_helper->layout()->disableLayout();
//        
//    }
    
//    public function cadastrarAction() {
//        $this->view->aplicacao = $this->_getAllParams();
//        $this->_helper->layout()->disableLayout();
//               
//        $dados = $this->_request->getPost();
//        
//        if (!$dados['convenioSubmit']) {
//            $result = $this->_modelTipoProfissional->inserir($dados);
//        } else {
//            $result = $this->_modelTipoProfissional->editar($dados);
//        }
//        
//        if ($result) {
//            echo "ok";
//        } else {
//            echo "naopassou";
//        }
//        
//        die();
//    }
    
//    public function listarAction() {
//        $convenio = $this->_request->getPost("convenio");
//        $input = $this->_request->getPost("input");
//        
//        $result = $this->_modelTipoProfissional->listarAutocomplete($convenio);
//        
//        if ($result) {
//            $html = "<table id='div_".$input."' style='width: 100%;' class='listagem' cellpadding='0' cellspacing='0' border='0'>";
//            $cont = 0;
//            foreach ($result as $r) {
//                $color = ($cont % 2 == 0) ? "bgcolor='#c4c4c4'" : "" ;
//                $html .= "<tr $color style='height: 25px;'>
//                            <td id='convenio_".$input."' convenio='" . $r['codAnsConvenio'] . "'>";
//                
//                $html .= $r['razaoSocialConvenio'];
//                
//                if($r['ativoConvenio'] == '0'){
//                    $id = "ativar_";
//                    $img = "/img/ativar_32x32.png";                   
//                }else{
//                    $id = "excluir_";
//                    $img = "/img/excluir_32x32.png";
//                }
//                
//                $html .= "  
//                            </td>
//                            <td width='30px' id='".$id.$input."' convenio='" . $r['codAnsConvenio'] . "'>
//                                <img src='" . dirname($_SERVER['PHP_SELF']) . $img. "' alt='' width='20px' />
//                            </td>
//                        </tr>";
//                $cont++;
//            }
//                            
//            $html .= "</table>";
//            
//            echo $html;
//        } else {
//            echo "<div id='div_".$input."'>Não existe registro para essa pesquisa</div>";
//        }
//        
//        die();
//    }
    
//    public function buscarconvenioAction() {
//        $convenio = $this->_request->getPost("codConvenio");
//        $result = $this->_modelTipoProfissional->buscar($convenio);
//        
//        
//        
//        if ($result) {            
//            echo json_encode($result, true);
//        } else {
//            echo "naopassou";
//        }
//        
//        die();
//    }
    
//    public function excluirconvenioAction() {
//        $convenio = $this->_request->getPost("codConvenio");
//        $result = $this->_modelTipoProfissional->ativarDesativar($convenio, '0');
//        
//        if ($result) {            
//            $result = Convenio::buscar($convenio);
//            echo $result['razaoSocialConvenio'];
//        } else {
//            echo "naopassou";
//        }
//        
//        die();
//    }
//    
//    public function ativarconvenioAction() {
//        $convenio = $this->_request->getPost("codConvenio");
//        $result = $this->_modelTipoProfissional->ativarDesativar($convenio, '1');
//        
//        if ($result) {            
//            $result = Convenio::buscar($convenio);
//            echo $result['razaoSocialConvenio'];
//        } else {
//            echo "naopassou";
//        }
//        
//        die();
//    }
}

