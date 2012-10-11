<?php
class Clinica_PlanoController extends Zend_Controller_Action {
    
    private $_usuario;
    private $_modelPlano;
    private $_modelUnidade;
    private $_modelConvenio;
    
    public function init() {
        parent::init();
        
        $this->_modelPlano = new Plano();
        $this->_modelUnidade = new Unidade();
        $this->_modelConvenio = new Convenio();
        
        if (!Usuario::isLogged()) {
            $this->_forward("index");
        } else {
            $this->_usuario = Usuario::isLogged();
            $this->view->usuario = $this->_usuario;
        }
    }
    
    public function planoAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
        
        $convenios = $this->_modelConvenio->fetchAll(null, "codAnsConvenio ASC");
        $this->view->convenios = $convenios;
        $unidades = $this->_modelUnidade->fetchAll(null, "codUnidade ASC");
        $this->view->unidades = $unidades;
    }
    
    public function cadastrarAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
        
        $dados = $this->_request->getPost();
        
        if (!$dados['planoSubmit']) {
            $result = $this->_modelPlano->inserir($dados);
        } else {
            $result = $this->_modelPlano->editar($dados);
        }
        
        if ($result) {
            echo "ok";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function listarAction() {
        $plano = $this->_request->getPost("plano");
        $input = $this->_request->getPost("input");
        
        $result = $this->_modelPlano->listarAutocomplete($plano);
        
        if ($result) {
            $html = "<table id='div_".$input."' style='width: 100%;' class='listagem' cellpadding='0' cellspacing='0' border='0'>";
            $cont = 0;
            foreach ($result as $r) {
                $color = ($cont % 2 == 0) ? "bgcolor='#c4c4c4'" : "" ;
                $html .= "<tr $color style='height: 25px;'>
                            <td id='plano_".$input."' plano='" . $r['codPlano'] . "'>";
                
                $html .= $r['nomePlano'];
                
                if($r['ativoPlano'] == '0'){
                    $id = "ativar_";
                    $img = "/img/ativar_32x32.png";                   
                }else{
                    $id = "excluir_";
                    $img = "/img/excluir_32x32.png";
                }
                
                $html .= "  
                            </td>
                            <td width='30px' id='".$id.$input."' plano='" . $r['codPlano'] . "'>
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
    
    public function buscarplanoAction() {
        $plano = $this->_request->getPost("codPlano");
        var_dump($plano);
        die();
        $result = $this->_modelPlano->buscar($plano);
                
        if ($result) {            
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function excluirplanoAction() {
        $plano = $this->_request->getPost("codPlano");
        $result = $this->_modelPlano->ativarDesativar($plano, '0');
        
        if ($result) {            
            $result = Plano::buscarId($plano);
            echo $result->nomePlano;            
        } else {
            echo "naopassou";
        }        
        die();
    }
    
    public function ativarplanoAction() {
        $plano = $this->_request->getPost("codPlano");
        $result = $this->_modelPlano->ativarDesativar($plano, '1');
        
        if ($result) {            
            $result = Plano::buscarId($plano);
            echo $result->nomePlano;            
        } else {
            echo "naopassou";
        }
        
        die();
    }
}