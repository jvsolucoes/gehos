<?php
/**
 * Description of EspecialidadeController
 *
 * @author luciana
 */
class Clinica_EspecialidadeController extends Zend_Controller_Action {

    private $_usuario;
    private $_modelEspecialidade;
    
    public function init() {
        parent::init();
        
        $this->_modelEspecialidade = new Especialidade();
        
        if (!Usuario::isLogged()) {
            $this->_forward("index");
        } else {
            $this->_usuario = Usuario::isLogged();
            $this->view->usuario = $this->_usuario;
        }
    }

    public function especialidadeAction() {
        $this->view->aplicacao = $this->_getAllParams();
        $this->_helper->layout()->disableLayout();
    }
    
    public function cadastrarAction() {
        $dados = $this->_request->getPost();
        
        if (!$dados['especialidadeSubmit']) {
            $result = $this->_modelEspecialidade->inserir($dados);
        } else {
            $result = $this->_modelEspecialidade->editar($dados);
        }
        
        
        if ($result) {
            echo "ok";
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function listarAction() {
        $especialidade = $this->_request->getPost("especialidade");
        $input = $this->_request->getPost("input");
        
        $result = $this->_modelEspecialidade->listarAutocomplete($especialidade);
        
        if ($result) {
            $html = "<table id='div_".$input."' style='width: 100%;' class='listagem' cellpadding='0' cellspacing='0' border='0'>";
            $cont = 0;
            foreach ($result as $r) {
                $color = ($cont % 2 == 0) ? "bgcolor='#c4c4c4'" : "" ;
                $html .= "<tr $color style='height: 25px;'>
                            <td id='especialidade_".$input."' especialidade='" . $r['codEspecialidade'] . "'>";
                
                $html .= $r['nomeEspecialidade'];
                
                if($r['ativoEspecialidade'] == '0'){
                    $id = "ativar_";
                    $img = "/img/ativar_32x32.png";                   
                }else{
                    $id = "excluir_";
                    $img = "/img/excluir_32x32.png";
                }
                
                $html .= "  
                            </td>
                            <td width='30px' id='".$id.$input."' especialidade='" . $r['codEspecialidade'] . "'>
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
    
    public function buscarespecialidadeAction() {
        $especialidade = $this->_request->getPost("codEspecialidade");
        $result = $this->_modelEspecialidade->buscar($especialidade);
        
        if ($result) {            
            echo json_encode($result, true);
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function excluirespecialidadeAction() {
        $especialidade = $this->_request->getPost("codEspecialidade");
        $result = $this->_modelEspecialidade->ativarDesativar($especialidade, '0');
        
        if ($result) {            
            $result = Especialidade::buscarId($especialidade);
            echo $result->nomeEspecialidade;            
        } else {
            echo "naopassou";
        }
        
        die();
    }
    
    public function ativarespecialidadeAction() {
        $especialidade = $this->_request->getPost("codEspecialidade");
        $result = $this->_modelEspecialidade->ativarDesativar($especialidade, '1');
        
        if ($result) {            
            $result = Especialidade::buscarId($especialidade);
            echo $result->nomeEspecialidade;            
        } else {
            echo "naopassou";
        }
        
        die();
    }
}