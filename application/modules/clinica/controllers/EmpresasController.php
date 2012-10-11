<?php
class Clinica_EmpresasController extends Zend_Controller_Action {
    
    private $_usuario;
    private $_modelEmpresa;
    
    public function init() {
        parent::init();
        
        $this->_modelEmpresa = new Empresa();
        
        if (!Usuario::isLogged()) {
            $this->_forward("index");
        } else {
            $this->_usuario = Usuario::isLogged();
            $this->view->usuario = $this->_usuario;
        }
    }
    
    public function empresasAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
        
    }
    
    public function cadastrarAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
        
        $dados = $this->_request->getPost();
        
        if (!$dados['empresaSubmit']) {
            $result = $this->_modelEmpresa->inserir($dados);
        } else {
            $result = $this->_modelEmpresa->editar($dados);
        }
        
        if ($result) {
            echo "ok";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function listarAction() {
        $empresa = $this->_request->getPost("empresa");
        $input = $this->_request->getPost("input");
        
        $result = $this->_modelEmpresa->listarAutocomplete($empresa);
        
        if ($result) {
            $html = "<table id='div_".$input."' style='width: 100%;' class='listagem' cellpadding='0' cellspacing='0' border='0'>";
            $cont = 0;
            foreach ($result as $r) {
                $color = ($cont % 2 == 0) ? "bgcolor='#c4c4c4'" : "" ;
                $html .= "<tr $color style='height: 25px;'>
                            <td id='empresa_".$input."' empresa='" . $r['codEmpresa'] . "'>";
                
                $html .= $r['razaoSocialEmpresa'];
                
                if($r['ativoEmpresa'] == '0'){
                    $id = "ativar_";
                    $img = "/img/ativar_32x32.png";                   
                }else{
                    $id = "excluir_";
                    $img = "/img/excluir_32x32.png";
                }
                
                $html .= "  
                            </td>
                            <td width='30px' id='".$id.$input."' empresa='" . $r['codEmpresa'] . "'>
                                <img src='" . dirname($_SERVER['PHP_SELF']) . $img. "' alt='' width='20px' />
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
    
    public function buscarempresaAction() {
        $empresa = $this->_request->getPost("codEmpresa");
        $result = $this->_modelEmpresa->buscar($empresa);
        
        if ($result) {            
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function excluirempresaAction() {
        $empresa = $this->_request->getPost("codEmpresa");
        $result = $this->_modelEmpresa->ativarDesativar($empresa, '0');
        
        if ($result) {            
            $result = Pessoa::buscar($empresa);
            echo $result['razaoSocialEmprea'];
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function ativarempresaAction() {
        $empresa = $this->_request->getPost("codEmpresa");
        $result = $this->_modelEmpresa->ativarDesativar($empresa, '1');
        
        if ($result) {            
            $result = Pessoa::buscar($empresa);
            echo $result['nomeFantasiaEmpresa'];
        } else {
            echo "naopassou";
        }
        
        die();
    }
}

