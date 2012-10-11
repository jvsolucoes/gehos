<?php
class Clinica_PacienteController extends Zend_Controller_Action {
    
    private $_usuario;
    private $_modelPessoa;
    private $_modelSexo;
    private $_modelEstadoCivil;
    private $_modelCidade;
    
    public function init() {
        parent::init();
        
        $this->_modelPessoa = new Pessoa();
        $this->_modelSexo = new Sexo();
        $this->_modelEstadoCivil = new EstadoCivil();
        $this->_modelCidade = new Cidade();
        
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
        
        $sexos = $this->_modelSexo->fetchAll(null, "sexo ASC");
        $this->view->sexos = $sexos;
        $estadoscivis = $this->_modelEstadoCivil->fetchAll(null, "estadoCivil ASC");
        $this->view->estadoscivis = $estadoscivis;
        $cidades = $this->_modelCidade->fetchAll(null, "nomeCidade ASC");
        $this->view->cidades = $cidades;
        
    }
    
    public function cadastrarAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
        
        $dados = $this->_request->getPost();
        
        if (!$dados['pessoaSubmit']) {
            $result = $this->_modelPessoa->inserir($dados);
        } else {
            $result = $this->_modelPessoa->editar($dados);
        }
        
        if ($result) {
            echo "ok";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function listarAction() {
        $pessoa = $this->_request->getPost("pessoa");
        $input = $this->_request->getPost("input");
        
        $result = $this->_modelPessoa->listarAutocomplete($pessoa);
        
        if ($result) {
            $html = "<table id='div_".$input."' style='width: 100%;' class='listagem' cellpadding='0' cellspacing='0' border='0'>";
            $cont = 0;
            foreach ($result as $r) {
                $color = ($cont % 2 == 0) ? "bgcolor='#c4c4c4'" : "" ;
                $html .= "<tr $color style='height: 25px;'>
                            <td id='pessoa_".$input."' pessoa='" . $r['codPessoa'] . "'>";
                
                $html .= $r['nomePessoa'];
                
                if($r['ativoPessoa'] == '0'){
                    $id = "ativar_";
                    $img = "/img/ativar_32x32.png";                   
                }else{
                    $id = "excluir_";
                    $img = "/img/excluir_32x32.png";
                }
                
                $html .= "  
                            </td>
                            <td width='30px' id='".$id.$input."' pessoa='" . $r['codPessoa'] . "'>
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
    
    public function buscarpessoaAction() {
        $pessoa = $this->_request->getPost("codPessoa");
        $result = $this->_modelPessoa->buscar($pessoa);
        
        if ($result) {            
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function excluirpessoaAction() {
        $pessoa = $this->_request->getPost("codPessoa");
        $result = $this->_modelPessoa->ativarDesativar($pessoa, '0');
        
        if ($result) {            
            $result = Pessoa::buscar($pessoa);
            echo $result['nomePessoa'];
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function ativarpessoaAction() {
        $pessoa = $this->_request->getPost("codPessoa");
        $result = $this->_modelPessoa->ativarDesativar($pessoa, '1');
        
        if ($result) {            
            $result = Pessoa::buscar($pessoa);
            echo $result['nomePessoa'];
        } else {
            echo "naopassou";
        }
        
        die();
    }
}

