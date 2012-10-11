<?php
class Clinica_UnidadesController extends Zend_Controller_Action {
    
    private $_usuario;
    private $_modelUnidade;
    private $_modelCidade;
    private $_modelEmpresa;
    
    public function init() {
        parent::init();
        
        $this->_modelUnidade = new Unidade();
        $this->_modelCidade = new Cidade();
        $this->_modelEmpresa = new Empresa();
        
        if (!Usuario::isLogged()) {
            $this->_forward("index");
        } else {
            $this->_usuario = Usuario::isLogged();
            $this->view->usuario = $this->_usuario;
        }
    }
    
    public function unidadesAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
        
        $cidades = $this->_modelCidade->fetchAll(null, "nomeCidade ASC");
        $this->view->cidades = $cidades;
        $empresas = $this->_modelEmpresa->fetchAll(null, "nomeFantasiaEmpresa ASC");
        $this->view->empresas = $empresas;
        
    }
    
    public function cadastrarAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
        
        $dados = $this->_request->getPost();
        
        if (!$dados['unidadeSubmit']) {
            $result = $this->_modelUnidade->inserir($dados);
        } else {
            $result = $this->_modelUnidade->editar($dados);
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
        $input = $this->_request->getPost("input");
        
        $result = $this->_modelUnidade->listarAutocomplete($unidade);
        
        if ($result) {
            $html = "<table id='div_".$input."' style='width: 100%;' class='listagem' cellpadding='0' cellspacing='0' border='0'>";
            $cont = 0;
            foreach ($result as $r) {
                $color = ($cont % 2 == 0) ? "bgcolor='#c4c4c4'" : "" ;
                $html .= "<tr $color style='height: 25px;'>
                            <td id='unidade_".$input."' unidade='" . $r['codUnidade'] . "'>";
                
                $html .= $r['nomeUnidade'];
                
                if($r['ativoUnidade'] == '0'){
                    $id = "ativar_";
                    $img = "/img/ativar_32x32.png";                   
                }else{
                    $id = "excluir_";
                    $img = "/img/excluir_32x32.png";
                }
                
                $html .= "  
                            </td>
                            <td width='30px' id='".$id.$input."' unidade='" . $r['codUnidade'] . "'>
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
    
    public function buscarAction() {
        $unidade = $this->_request->getPost("codUnidade");
        $result = $this->_modelUnidade->buscar($unidade);
        
        
        
        if ($result) {            
            $nomeEmpresa = Empresa::buscar($result['codEmpresa']);
            $result['codEmpresa'] = $nomeEmpresa['nomeFantasiaEmpresa'];
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function excluirunidadeAction() {
        $unidade = $this->_request->getPost("codUnidade");
        $result = $this->_modelUnidade->ativarDesativar($unidade, '0');
        
        if ($result) {            
            $result = Unidade::buscarId($unidade);
            echo $result->nomeUnidade;            
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function ativarunidadeAction() {
        $unidade = $this->_request->getPost("codUnidade");
        $result = $this->_modelUnidade->ativarDesativar($unidade, '1');
        
        if ($result) {            
            $result = Unidade::buscarId($unidade);
            echo $result->nomeUnidade;            
        } else {
            echo "naopassou";
        }
        
        die();
    }
}

